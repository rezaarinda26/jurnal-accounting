<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function trialBalance(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));
        $view = $request->input('view', 'account'); // 'account' or 'pic'

        if ($view === 'pic') {
            $data = $this->getPICBalanceData($startDate, $endDate);
            $totalDebit = $data->sum('total_debit');
            $totalCredit = $data->sum('total_credit');
            return view('reports.trial_balance', compact('data', 'totalDebit', 'totalCredit', 'startDate', 'endDate', 'view'));
        }

        $accounts = $this->getTrialBalanceData($startDate, $endDate);
        $totalDebit = $accounts->sum('total_debit');
        $totalCredit = $accounts->sum('total_credit');

        return view('reports.trial_balance', compact('accounts', 'totalDebit', 'totalCredit', 'startDate', 'endDate', 'view'));
    }

    public function generalLedger(Request $request)
    {
        $accountId = $request->input('account_id');
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $accounts = Account::orderBy('code')->get();
        $selectedAccount = null;
        $ledgerEntries = collect();
        $openingBalance = 0;
        $normalBalance = 'debit'; // default

        if ($accountId) {
            $selectedAccount = Account::findOrFail($accountId);
            
            // Determine normal balance based on code prefix
            // 1: Asset (D), 2: Liability (C), 3: Equity (C), 4: Revenue (C), 5: Expense (D)
            $prefix = substr($selectedAccount->code, 0, 1);
            if (in_array($prefix, ['2', '3', '4'])) {
                $normalBalance = 'credit';
            }

            // Calculate opening balance
            $openingDebit = \App\Models\JournalEntry::where('account_id', $accountId)
                ->whereHas('journal', function ($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })
                ->where('is_debit', true)
                ->sum('amount');
            
            $openingCredit = \App\Models\JournalEntry::where('account_id', $accountId)
                ->whereHas('journal', function ($q) use ($startDate) {
                    $q->where('date', '<', $startDate);
                })
                ->where('is_debit', false)
                ->sum('amount');
            
            if ($normalBalance === 'debit') {
                $openingBalance = $openingDebit - $openingCredit;
            } else {
                $openingBalance = $openingCredit - $openingDebit;
            }

            // Fetch entries
            $ledgerEntries = \App\Models\JournalEntry::with('journal')
                ->where('account_id', $accountId)
                ->whereHas('journal', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('date', [$startDate, $endDate]);
                })
                ->join('journals', 'journal_entries.journal_id', '=', 'journals.id')
                ->orderBy('journals.date')
                ->orderBy('journals.id')
                ->select('journal_entries.*')
                ->get();
        }

        return view('reports.general_ledger', compact(
            'accounts', 'selectedAccount', 'ledgerEntries', 
            'openingBalance', 'startDate', 'endDate', 'accountId', 'normalBalance'
        ));
    }

    public function exportTrialBalance(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $view = $request->input('view', 'account');

        if ($view === 'pic') {
            $data = $this->getPICBalanceData($startDate, $endDate);
            $totalDebit = $data->sum('total_debit');
            $totalCredit = $data->sum('total_credit');

            $fileName = 'Rekapitulasi_Saldo_PIC_';
            $fileName .= $startDate . '_to_' . $endDate . '_';
            $fileName .= date('Ymd_His') . '.xlsx';

            $writer = \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload($fileName);

            foreach ($data as $row) {
                $writer->addRow([
                    'Nama PIC' => $row->pic_name,
                    'Debit' => $row->total_debit ?? 0,
                    'Kredit' => $row->total_credit ?? 0,
                ]);
            }

            $writer->addRow([
                'Nama PIC' => 'TOTAL',
                'Debit' => $totalDebit,
                'Kredit' => $totalCredit,
            ]);

            return $writer->toBrowser();
        }

        $accounts = $this->getTrialBalanceData($startDate, $endDate);

        $totalDebit = $accounts->sum('total_debit');
        $totalCredit = $accounts->sum('total_credit');

        $fileName = 'Rekapitulasi_Saldo_';
        $fileName .= $startDate . '_to_' . $endDate . '_';
        $fileName .= date('Ymd_His') . '.xlsx';

        $writer = \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload($fileName);

        foreach ($accounts as $account) {
            $writer->addRow([
                'Kode Akun' => $account->code,
                'Nama Akun' => $account->name,
                'Debit' => $account->total_debit ?? 0,
                'Kredit' => $account->total_credit ?? 0,
            ]);
        }

        $writer->addRow([
            'Kode Akun' => '',
            'Nama Akun' => 'TOTAL',
            'Debit' => $totalDebit,
            'Kredit' => $totalCredit,
        ]);

        return $writer->toBrowser();
    }

    public function exportGeneralLedger(Request $request)
    {
        $accountId = $request->input('account_id');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if (!$accountId) {
            return back()->with('error', 'Pilih akun terlebih dahulu untuk diekspor.');
        }

        $selectedAccount = Account::findOrFail($accountId);
        $prefix = substr($selectedAccount->code, 0, 1);
        $normalBalance = in_array($prefix, ['2', '3', '4']) ? 'credit' : 'debit';

        // Calculate opening balance
        $openingDebit = \App\Models\JournalEntry::where('account_id', $accountId)
            ->whereHas('journal', function ($q) use ($startDate) {
                $q->where('date', '<', $startDate);
            })
            ->where('is_debit', true)
            ->sum('amount');
        
        $openingCredit = \App\Models\JournalEntry::where('account_id', $accountId)
            ->whereHas('journal', function ($q) use ($startDate) {
                $q->where('date', '<', $startDate);
            })
            ->where('is_debit', false)
            ->sum('amount');
        
        $openingBalance = $normalBalance === 'debit' ? ($openingDebit - $openingCredit) : ($openingCredit - $openingDebit);

        // Fetch entries
        $ledgerEntries = \App\Models\JournalEntry::with('journal')
            ->where('account_id', $accountId)
            ->whereHas('journal', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('date', [$startDate, $endDate]);
            })
            ->join('journals', 'journal_entries.journal_id', '=', 'journals.id')
            ->orderBy('journals.date')
            ->orderBy('journals.id')
            ->select('journal_entries.*')
            ->get();

        $fileName = 'Buku_Besar_' . str_replace(' ', '_', $selectedAccount->name) . '_';
        $fileName .= $startDate . '_to_' . $endDate . '.xlsx';

        $writer = \Spatie\SimpleExcel\SimpleExcelWriter::streamDownload($fileName);

        // Header and Opening Balance
        $writer->addRow([
            'Tanggal' => \Carbon\Carbon::parse($startDate)->format('Y-m-d'),
            'Referensi' => '-',
            'Keterangan' => 'SALDO AWAL',
            'Debit (Rp)' => '-',
            'Kredit (Rp)' => '-',
            'Saldo Berjalan (Rp)' => $openingBalance,
        ]);

        $currentBalance = $openingBalance;

        foreach ($ledgerEntries as $entry) {
            if ($normalBalance === 'debit') {
                $currentBalance += ($entry->is_debit ? $entry->amount : -$entry->amount);
            } else {
                $currentBalance += ($entry->is_debit ? -$entry->amount : $entry->amount);
            }

            $writer->addRow([
                'Tanggal' => $entry->journal->date,
                'Referensi' => $entry->journal->journal_number,
                'Keterangan' => $entry->journal->description . ' (PIC: ' . $entry->journal->pic_name . ')',
                'Debit (Rp)' => $entry->is_debit ? $entry->amount : 0,
                'Kredit (Rp)' => !$entry->is_debit ? $entry->amount : 0,
                'Saldo Berjalan (Rp)' => $currentBalance,
            ]);
        }

        return $writer->toBrowser();
    }

    /**
     * Mengambil data akun untuk laporan trial balance dengan filter saldo.
     */
    private function getTrialBalanceData($startDate = null, $endDate = null)
    {
        $query = Account::query();

        $query->withSum(['entries as total_debit' => function ($q) use ($startDate, $endDate) {
            $q->where('is_debit', true);
            if ($startDate && $endDate) {
                $q->whereHas('journal', function ($jq) use ($startDate, $endDate) {
                    $jq->whereBetween('date', [$startDate, $endDate]);
                });
            }
        }], 'amount');

        $query->withSum(['entries as total_credit' => function ($q) use ($startDate, $endDate) {
            $q->where('is_debit', false);
            if ($startDate && $endDate) {
                $q->whereHas('journal', function ($jq) use ($startDate, $endDate) {
                    $jq->whereBetween('date', [$startDate, $endDate]);
                });
            }
        }], 'amount');

        return $query->orderBy('code')
            ->get()
            ->filter(function ($account) {
                return ($account->total_debit ?? 0) > 0 || ($account->total_credit ?? 0) > 0;
            });
    }

    private function getPICBalanceData($startDate = null, $endDate = null)
    {
        $picBalances = \Illuminate\Support\Facades\DB::table('journals')
            ->join('journal_entries', 'journals.id', '=', 'journal_entries.journal_id')
            ->join('accounts', 'journal_entries.account_id', '=', 'accounts.id')
            ->where('accounts.name', '!=', 'Kas')
            ->select('journals.pic_name')
            ->selectRaw('SUM(CASE WHEN journal_entries.is_debit = true THEN journal_entries.amount ELSE 0 END) as total_debit')
            ->selectRaw('SUM(CASE WHEN journal_entries.is_debit = false THEN journal_entries.amount ELSE 0 END) as total_credit')
            ->groupBy('journals.pic_name');

        if ($startDate && $endDate) {
            $picBalances->whereBetween('journals.date', [$startDate, $endDate]);
        }

        $results = $picBalances->get();

        // Fetch breakdown per account for each PIC
        foreach ($results as $pic) {
            $details = \Illuminate\Support\Facades\DB::table('journals')
                ->join('journal_entries', 'journals.id', '=', 'journal_entries.journal_id')
                ->join('accounts', 'journal_entries.account_id', '=', 'accounts.id')
                ->where('journals.pic_name', $pic->pic_name)
                ->where('accounts.name', '!=', 'Kas')
                ->select('accounts.code', 'accounts.name')
                ->selectRaw('SUM(CASE WHEN journal_entries.is_debit = true THEN journal_entries.amount ELSE 0 END) as total_debit')
                ->selectRaw('SUM(CASE WHEN journal_entries.is_debit = false THEN journal_entries.amount ELSE 0 END) as total_credit')
                ->groupBy('accounts.code', 'accounts.name')
                ->orderBy('accounts.code');

            if ($startDate && $endDate) {
                $details->whereBetween('journals.date', [$startDate, $endDate]);
            }

            $pic->details = $details->get();
        }

        return $results;
    }
}
