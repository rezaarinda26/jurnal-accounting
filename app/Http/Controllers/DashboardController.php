<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use App\Models\Journal;
use App\Models\Account;
use App\Models\Bundle;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $currentMonth = date('m');
        $currentYear = date('Y');
        
        $totalMonth = JournalEntry::where('is_debit', true)
            ->whereHas('journal', function($q) use ($currentMonth, $currentYear) {
                $q->whereMonth('date', $currentMonth)
                  ->whereYear('date', $currentYear);
            })->sum('amount');

        $totalJournalsThisMonth = Journal::whereMonth('date', $currentMonth)
            ->whereYear('date', $currentYear)
            ->count();

        $activeBundles = Bundle::where('status', 'open')->count();
        $totalAccounts = Account::count();

        $recentJournals = Journal::with(['entries', 'bundle'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalMonth', 
            'totalJournalsThisMonth', 
            'activeBundles', 
            'totalAccounts',
            'recentJournals'
        ));
    }
}
