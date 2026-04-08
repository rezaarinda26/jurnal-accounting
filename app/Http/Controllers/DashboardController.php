<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
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

        return view('dashboard', compact('totalMonth'));
    }
}
