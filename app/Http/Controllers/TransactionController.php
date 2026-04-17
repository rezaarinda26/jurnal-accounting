<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\Account;
use App\Models\Pic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('bundle_id')) {
            $bundle = \App\Models\Bundle::findOrFail($request->bundle_id);
            
            $sort = $request->input('sort', 'created_at');
            $direction = $request->input('direction', 'desc');

            // Allow sorting by date (Tanggal Transaksi) or created_at (Tanggal Input)
            $validSorts = ['date', 'created_at'];
            if (!in_array($sort, $validSorts)) {
                $sort = 'created_at';
            }
            
            $validDirections = ['asc', 'desc'];
            if (!in_array($direction, $validDirections)) {
                $direction = 'desc';
            }

            $journals = $bundle->journals()->with('entries.account')->orderBy($sort, $direction)->paginate(15)->withQueryString();
            return view('transactions.index', compact('journals', 'sort', 'direction', 'bundle'));
        }

        if ($request->has('type')) {
            $type = $request->type;
            $bundles = \App\Models\Bundle::where('type', $type)
                ->withCount('journals')
                ->orderBy('created_at', 'desc')
                ->get();
            $accounts = Account::where('name', '!=', 'Kas')->orderBy('name')->get();
            return view('transactions.bundles', compact('bundles', 'accounts', 'type'));
        }

        return view('transactions.select_type');
    }

    public function create(Request $request)
    {
        $openBundle = null;
        if ($request->has('bundle_id')) {
            $openBundle = \App\Models\Bundle::where('status', 'open')->find($request->bundle_id);
        } else {
            $openBundle = \App\Models\Bundle::where('status', 'open')->latest('id')->first();
        }

        if (!$openBundle) {
            return redirect()->route('transactions.index')
                ->withErrors(['Tidak ada Bundle yang terbuka. Silakan Buka Bundle Baru terlebih dahulu sebelum mencatat transaksi.']);
        }

        $accounts = Account::where('name', '!=', 'Kas')->orderBy('name')->get();
        $pics = Pic::orderBy('name')->get();
        // Generate automatic journal number
        $lastJournal = Journal::latest('id')->first();
        $nextId = $lastJournal ? $lastJournal->id + 1 : 1;
        $newNumber = 'OUT-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        return view('transactions.create', compact('accounts', 'pics', 'newNumber', 'openBundle'));
    }

    public function store(Request $request)
    {
        $openBundle = \App\Models\Bundle::where('status', 'open')->find($request->bundle_id);
        
        if (!$openBundle) {
            return redirect()->route('transactions.index')
                ->withErrors(['Bundle tidak valid atau sudah ditutup.']);
        }

        $request->validate([
            'bundle_id' => 'required|exists:bundles,id',
            'journal_number' => 'required|unique:journals,journal_number',
            'date' => 'required|date',
            'pic_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'entries'           => 'required|array|min:1',
            'entries.*.account_id' => 'required|exists:accounts,id',
            'entries.*.amount'  => 'required|numeric|min:0',
            'entries.*.description' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request, $openBundle) {
            $journal = Journal::create([
                'journal_number' => $request->journal_number,
                'date' => $request->date,
                'pic_name' => $request->pic_name,
                'description' => $request->description,
                'bundle_id' => $openBundle->id,
            ]);

            $totalAmount = 0;

            foreach ($request->entries as $entry) {
                JournalEntry::create([
                    'journal_id' => $journal->id,
                    'account_id' => $entry['account_id'],
                    'description' => $entry['description'] ?? null,
                    'amount' => $entry['amount'],
                    'is_debit' => true,
                ]);
                $totalAmount += $entry['amount'];
            }

            // Reverse entry for Kas (Credit)
            $kasAccount = Account::firstOrCreate(
                ['code' => '1-110'],
                ['name' => 'Kas']
            );

            JournalEntry::create([
                'journal_id' => $journal->id,
                'account_id' => $kasAccount->id,
                'description' => 'Pembayaran Kas (Otomatis) - ' . $request->pic_name,
                'amount' => $totalAmount,
                'is_debit' => false,
            ]);
        });

        return redirect()->route('transactions.index', ['bundle_id' => $openBundle->id])->with('success', 'Transaksi berhasil disimpan.');
    }

    public function show(Journal $transaction)
    {
        $transaction->load('entries.account');
        return view('transactions.show', ['journal' => $transaction]);
    }

    public function exportExcel(Request $request)
    {
        $query = Journal::with(['entries.account', 'bundle']);

        if ($request->filled('pic')) {
            $query->where('pic_name', 'ilike', '%' . $request->pic . '%');
        }

        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('date', $parts[0])
                      ->whereMonth('date', $parts[1]);
            }
        }

        if ($request->filled('nominal')) {
            $nominal = $request->nominal;
            $query->whereHas('entries', function ($q) use ($nominal) {
                $q->where('amount', $nominal);
            });
        }

        if ($request->filled('account_id')) {
            $accountId = $request->account_id;
            $query->whereHas('entries', function ($q) use ($accountId) {
                $q->where('account_id', $accountId);
            });
        }

        $sort = $request->input('sort', 'date');
        $direction = $request->input('direction', 'desc');

        $validSorts = ['date', 'created_at'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'date';
        }
        
        $validDirections = ['asc', 'desc'];
        if (!in_array($direction, $validDirections)) {
            $direction = 'desc';
        }

        $journals = $query->orderBy($sort, $direction)->get();

        $fileName = 'Data_Jurnal_Transaksi_' . date('Ymd_His') . '.xlsx';

        $writer = \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload($fileName);

        foreach ($journals as $journal) {
            $bundleType = $journal->bundle ? ucfirst($journal->bundle->type) : 'Tanpa Bundle';
            $bundleNo = $journal->bundle ? $journal->bundle->bundle_number : '-';

            // Temukan entri kas (kredit) untuk mendapatkan nomor akun kas
            $kasEntry = $journal->entries->firstWhere('is_debit', false);
            $kasCode = $kasEntry ? ($kasEntry->account->code ?? '-') : '-';

            foreach($journal->entries->filter(fn($e) => $e->is_debit) as $entry) {
                // Khusus untuk entri PPN, ganti keterangan transaksi menjadi "PPN"
                $isPpn = str_contains(strtolower($entry->account->name), 'ppn') || 
                         str_contains(strtolower($entry->account->code), 'ppn');
                
                $description = $isPpn ? 'PPN' : ($journal->description ?? '-');

                $writer->addRow([
                    'Tanggal' => \Carbon\Carbon::parse($journal->date)->format('d/m/Y'),
                    'Akun Kas' => $kasCode,
                    'Keterangan Transaksi' => $description,
                    'Akun Lawan' => $entry->account->code ?? '-',
                    'Keterangan Akun' => $entry->account->name,
                    'Debit' => $entry->amount,
                    'Kredit' => 0,
                    'PIC' => $journal->pic_name,
                    'Catatan' => $entry->description ?? '-',
                    'Tipe Bundle' => $bundleType,
                    'No Bundle' => $bundleNo,
                    'Nomor Referensi' => $journal->journal_number,
                ]);
            }
        }

        return $writer->toBrowser();
    }

    public function journal(Request $request)
    {
        $query = Journal::with(['entries.account', 'bundle']);

        if ($request->filled('pic')) {
            $query->where('pic_name', 'like', '%' . $request->pic . '%');
        }

        if ($request->filled('month')) { // format: YYYY-MM
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('date', $parts[0])
                      ->whereMonth('date', $parts[1]);
            }
        }

        if ($request->filled('nominal')) {
            $nominal = $request->nominal;
            $query->whereHas('entries', function ($q) use ($nominal) {
                $q->where('amount', $nominal);
            });
        }

        if ($request->filled('account_id')) {
            $accountId = $request->account_id;
            $query->whereHas('entries', function ($q) use ($accountId) {
                $q->where('account_id', $accountId);
            });
        }

        $sort = $request->input('sort', 'date');
        $direction = $request->input('direction', 'desc');

        $validSorts = ['date', 'created_at'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'date';
        }
        
        $validDirections = ['asc', 'desc'];
        if (!in_array($direction, $validDirections)) {
            $direction = 'desc';
        }

        $journals = $query->orderBy($sort, $direction)->paginate(25)->withQueryString();
        $accounts = Account::where('name', '!=', 'Kas')->orderBy('name')->get();
        $pics = Pic::orderBy('name')->get();

        return view('transactions.journal', compact('journals', 'accounts', 'pics', 'sort', 'direction'));
    }

    public function printVoucher(Journal $transaction)
    {
        $transaction->load(['entries.account', 'bundle']);
        
        $journals = collect([$transaction]);
        $bundle = $transaction->bundle;
        
        return view('transactions.print-batch', compact('journals', 'bundle'));
    }

    public function search(Request $request)
    {
        return redirect()->route('transactions.journal', $request->all());
    }

    public function destroy(Journal $transaction)
    {
        $bundleId = $transaction->bundle_id;
        $transaction->delete();
        
        if ($bundleId) {
            return redirect()->route('transactions.index', ['bundle_id' => $bundleId])->with('success', 'Transaksi berhasil dihapus.');
        }
        
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
