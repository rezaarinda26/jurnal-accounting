<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Journal;
use App\Models\Account;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View|\Illuminate\Http\RedirectResponse
    {
        // Finance users are not allowed on the dashboard
        if (auth()->check() && auth()->user()->isFinance()) {
            return redirect()->route('payables.index');
        }

        // Cache dashboard calculations for 10 minutes, cleared on data changes
        $stats = Cache::remember('dashboard_stats', 600, function() {
            $currentMonth = date('m');
            $currentYear = date('Y');
            
            // 1. Total Pengeluaran Bulan Ini
            $totalMonth = JournalEntry::join('journals', 'journal_entries.journal_id', '=', 'journals.id')
                ->where('journal_entries.is_debit', true)
                ->whereMonth('journals.date', $currentMonth)
                ->whereYear('journals.date', $currentYear)
                ->sum('journal_entries.amount');

            // 2. Total Pengeluaran Tahun Ini (YTD)
            $totalYear = JournalEntry::join('journals', 'journal_entries.journal_id', '=', 'journals.id')
                ->where('journal_entries.is_debit', true)
                ->whereYear('journals.date', $currentYear)
                ->sum('journal_entries.amount');

            // 3. PPN Masukan Bulan Ini
            $totalPpnMasukan = JournalEntry::join('journals', 'journal_entries.journal_id', '=', 'journals.id')
                ->join('accounts', 'journal_entries.account_id', '=', 'accounts.id')
                ->where('journal_entries.is_debit', true)
                ->whereMonth('journals.date', $currentMonth)
                ->whereYear('journals.date', $currentYear)
                ->where(function($q) {
                    $q->where('accounts.name', 'LIKE', '%PPN%')
                      ->orWhere('accounts.code', 'LIKE', '%PPN%');
                })
                ->sum('journal_entries.amount');

            // 4. Hutang PPh 23 Outstanding (Kredit - Debit pada akun Hutang PPh 23)
            $pphAccount = Account::where('name', 'LIKE', '%PPh%')
                ->orWhere('code', 'LIKE', '%pph%')
                ->first();

            $hutangPph23 = 0;
            if ($pphAccount) {
                $totalKredit = JournalEntry::where('account_id', $pphAccount->id)
                    ->where('is_debit', false)->sum('amount');
                $totalDebit = JournalEntry::where('account_id', $pphAccount->id)
                    ->where('is_debit', true)->sum('amount');
                $hutangPph23 = max(0, $totalKredit - $totalDebit);
            }

            // 5. Top PIC Bulan Ini (by total spending)
            $topPicThisMonth = Journal::join('journal_entries', 'journals.id', '=', 'journal_entries.journal_id')
                ->where('journal_entries.is_debit', true)
                ->whereMonth('journals.date', $currentMonth)
                ->whereYear('journals.date', $currentYear)
                ->select('journals.pic_name', DB::raw('SUM(journal_entries.amount) as total'))
                ->groupBy('journals.pic_name')
                ->orderByDesc('total')
                ->first();

            // 6. Pengeluaran per Kategori (Yearly)
            $expensesByCategory = JournalEntry::join('journals', 'journal_entries.journal_id', '=', 'journals.id')
                ->join('accounts', 'journal_entries.account_id', '=', 'accounts.id')
                ->where('journal_entries.is_debit', true)
                ->whereYear('journals.date', $currentYear)
                ->select(
                    DB::raw("CONCAT(accounts.code, ' - ', accounts.name) as label"),
                    DB::raw("CAST(SUM(journal_entries.amount) AS FLOAT) as total"),
                    'accounts.id as account_id'
                )
                ->groupBy('accounts.id', 'accounts.code', 'accounts.name')
                ->orderByDesc('total')
                ->get();

            // 7. Jumlah Transaksi Bulan Ini
            $totalJournalsThisMonth = Journal::whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->count();

            // 8. Trend 5 Bulan Terakhir
            $startDate = now()->subMonths(4)->startOfMonth()->format('Y-m-d');
            $endDate = now()->endOfMonth()->format('Y-m-d');

            $trendDataRaw = JournalEntry::join('journals', 'journal_entries.journal_id', '=', 'journals.id')
                ->where('journal_entries.is_debit', true)
                ->whereBetween('journals.date', [$startDate, $endDate])
                ->select(
                    DB::raw("to_char(journals.date, 'YYYY-MM') as month_key"),
                    DB::raw("SUM(journal_entries.amount) as total")
                )
                ->groupBy('month_key')
                ->pluck('total', 'month_key');

            $monthlyTrend = [];
            for ($i = 4; $i >= 0; $i--) {
                $targetDate = now()->subMonths($i);
                $key = $targetDate->format('Y-m');
                $monthlyTrend[] = [
                    'label' => $targetDate->translatedFormat('M Y'),
                    'total' => (float) ($trendDataRaw->get($key, 0))
                ];
            }

            return [
                'totalMonth'           => (float) $totalMonth,
                'totalYear'            => (float) $totalYear,
                'totalJournalsThisMonth' => $totalJournalsThisMonth,
                'totalPpnMasukan'      => (float) $totalPpnMasukan,
                'hutangPph23'          => (float) $hutangPph23,
                'topPicThisMonth'      => $topPicThisMonth,
                'expensesByCategory'   => $expensesByCategory,
                'monthlyTrend'         => $monthlyTrend,
            ];
        });

        // 6. Recent Journals (Eager-loaded, don't cache or cache separately if needed)
        $recentJournals = Journal::with(['entries', 'bundle'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', array_merge($stats, compact('recentJournals')));
    }
}
