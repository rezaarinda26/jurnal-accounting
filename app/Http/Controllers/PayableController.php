<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Journal;
use App\Models\JournalEntry;
use App\Models\Payable;
use App\Models\Pic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayableController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', '');

        $query = Payable::with(['journal', 'pic'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $payables       = $query->paginate(20)->withQueryString();
        $totalPending   = Payable::where('status', 'pending')->sum('amount');
        $totalPosted    = Payable::where('status', 'posted')->sum('amount');
        $countPending   = Payable::where('status', 'pending')->count();

        return view('payables.index', compact('payables', 'status', 'totalPending', 'totalPosted', 'countPending'));
    }

    public function create()
    {
        $pics = Pic::orderBy('name')->get();
        return view('payables.create', compact('pics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_number' => 'nullable|string|max:100',
            'pic_id'         => 'required|exists:pics,id',
            'description'    => 'required|string',
            'amount'         => 'required|numeric|min:1',
            'due_date'       => 'nullable|date',
        ]);

        $payable = Payable::create($validated);
        $payable->load('pic');

        return redirect()->route('payables.index')
            ->with('success', 'Tagihan dari "' . $payable->pic->name . '" berhasil dicatat.');
    }

    /**
     * Show the posting confirmation page for Akunting.
     */
    public function postShow(Payable $payable)
    {
        // Only Admin (Akunting) can access the posting page
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('payables.index')
                ->withErrors(['Anda tidak memiliki akses untuk memposting tagihan.']);
        }

        if ($payable->status !== 'pending') {
            return redirect()->route('payables.index')
                ->with('error', 'Tagihan ini sudah berstatus "' . $payable->status . '" dan tidak dapat diposting ulang.');
        }

        $payable->load('pic');
        $accounts = Account::orderBy('code')->get();

        return view('payables.post', compact('payable', 'accounts'));
    }

    /**
     * Process the posting: create journal entries and mark payable as posted.
     */
    public function processPost(Request $request, Payable $payable)
    {
        // Only Admin (Akunting) can process posting
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('payables.index')
                ->withErrors(['Anda tidak memiliki akses untuk memposting tagihan.']);
        }

        if ($payable->status !== 'pending') {
            return redirect()->route('payables.index')
                ->with('error', 'Tagihan ini sudah diposting sebelumnya.');
        }

        $validated = $request->validate([
            'debit_account_id'  => 'required|exists:accounts,id',
            'credit_account_id' => 'required|exists:accounts,id|different:debit_account_id',
            'journal_date'      => 'required|date',
            'notes'             => 'nullable|string',
        ]);

        $payable->load('pic');

        DB::transaction(function () use ($validated, $payable) {
            // Auto-generate a journal number
            $lastJournal = Journal::where('journal_number', 'like', 'HU-' . date('Ymd') . '-%')->orderBy('id', 'desc')->first();
            $nextId = $lastJournal ? (intval(substr($lastJournal->journal_number, -4)) + 1) : 1;
            $journalNumber = 'HU-' . date('Ymd') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // Create the main journal
            $journal = Journal::create([
                'journal_number' => $journalNumber,
                'date'           => $validated['journal_date'],
                'pic_name'       => $payable->pic->name, // Using PIC name from relationship
                'description'    => $validated['notes'] ?? $payable->description,
            ]);

            // Create journal entries (Debit & Credit)
            JournalEntry::create([
                'journal_id'  => $journal->id,
                'account_id'  => $validated['debit_account_id'],
                'amount'      => $payable->amount,
                'description' => $payable->description,
                'is_debit'    => true,
            ]);

            JournalEntry::create([
                'journal_id'  => $journal->id,
                'account_id'  => $validated['credit_account_id'],
                'amount'      => $payable->amount,
                'description' => $payable->description,
                'is_debit'    => false,
            ]);

            // Update the payable status
            $payable->update([
                'status'     => 'posted',
                'journal_id' => $journal->id,
            ]);
        });

        return redirect()->route('payables.index')
            ->with('success', 'Tagihan berhasil diposting ke Jurnal Umum.');
    }

    /**
     * Delete a payable entry if it's still pending.
     */
    public function destroy(Payable $payable)
    {
        // Only pending payables can be deleted
        if ($payable->status !== 'pending') {
            return redirect()->route('payables.index')
                ->with('error', 'Tagihan yang sudah diposting tidak dapat dihapus langsung. Jurnal terkait harus dihapus terlebih dahulu oleh Admin.');
        }

        $payable->delete();

        return redirect()->route('payables.index')
            ->with('success', 'Data tagihan berhasil dihapus.');
    }

    /**
     * Show the payment form for a posted payable.
     */
    public function payShow(Payable $payable)
    {
        if ($payable->status !== 'posted') {
            return redirect()->route('payables.index')
                ->with('error', 'Tagihan harus berstatus "Dijurnal" sebelum dapat dibayar.');
        }

        $payable->load('pic');
        // Only get bundles of type 'vendor' or 'operasional' that are still 'open'
        $bundles = \App\Models\Bundle::where('status', 'open')
            ->whereIn('type', ['vendor', 'operasional'])
            ->orderBy('created_at', 'desc')
            ->get();

        if ($bundles->isEmpty()) {
            return redirect()->route('payables.index')
                ->with('error', 'Tidak ada Bundle Vendor/Operasional yang terbuka. Silakan buka bundle baru terlebih dahulu.');
        }

        return view('payables.pay', compact('payable', 'bundles'));
    }

    /**
     * Process the payment of a payable.
     */
    public function processPay(Request $request, Payable $payable)
    {
        if ($payable->status !== 'posted') {
            return redirect()->route('payables.index')
                ->with('error', 'Tagihan ini tidak dapat dibayar.');
        }

        $request->validate([
            'bundle_id'    => 'required|exists:bundles,id',
            'payment_date' => 'required|date',
            'notes'        => 'nullable|string',
        ]);

        $bundle = \App\Models\Bundle::find($request->bundle_id);
        $payable->load('pic');

        DB::transaction(function () use ($request, $payable, $bundle) {
            // 1. Generate Journal Number for Payment (OUT-)
            $lastJournal = Journal::where('journal_number', 'like', 'OUT-' . date('Ymd', strtotime($request->payment_date)) . '-%')->orderBy('id', 'desc')->first();
            $nextId = $lastJournal ? (intval(substr($lastJournal->journal_number, -4)) + 1) : 1;
            $journalNumber = 'OUT-' . date('Ymd', strtotime($request->payment_date)) . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            // 2. Create the Payment Journal
            $journal = Journal::create([
                'journal_number' => $journalNumber,
                'date'           => $request->payment_date,
                'pic_id'         => $payable->pic_id,
                'pic_name'       => $payable->pic->name,
                'description'    => $request->notes ?? ('Pelunasan: ' . $payable->description),
                'bundle_id'      => $bundle->id,
            ]);

            // 3. Create Entries
            // Debit: Hutang Usaha (2-110)
            $payableAccount = Account::where('code', '2-110')->first();
            JournalEntry::create([
                'journal_id'  => $journal->id,
                'account_id'  => $payableAccount->id,
                'amount'      => $payable->amount,
                'description' => 'Pelunasan Tagihan ' . ($payable->invoice_number ?? $payable->description),
                'is_debit'    => true,
            ]);

            // Credit: Kas (1-110)
            $kasAccount = Account::where('code', '1-110')->first();
            JournalEntry::create([
                'journal_id'  => $journal->id,
                'account_id'  => $kasAccount->id,
                'amount'      => $payable->amount,
                'description' => 'Pembayaran Kas - ' . $payable->pic->name,
                'is_debit'    => false,
            ]);

            // 4. Mark Payable as Paid
            $payable->update([
                'status' => 'paid',
                // Keep the original journal_id (the recognition journal)
            ]);
        });

        return redirect()->route('payables.index')
            ->with('success', 'Tagihan dari "' . $payable->pic->name . '" telah berhasil dilunasi.');
    }
}
