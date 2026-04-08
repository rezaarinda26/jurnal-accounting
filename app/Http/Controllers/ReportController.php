<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function trialBalance(Request $request)
    {
        $accounts = Account::withSum(['entries as total_debit' => function ($query) {
                $query->where('is_debit', true);
            }], 'amount')
            ->withSum(['entries as total_credit' => function ($query) {
                $query->where('is_debit', false);
            }], 'amount')
            ->orderBy('code')
            ->get();

        $accounts = $accounts->filter(function($account) {
            return $account->total_debit > 0 || $account->total_credit > 0;
        });

        $totalDebit = $accounts->sum('total_debit');
        $totalCredit = $accounts->sum('total_credit');

        return view('reports.trial_balance', compact('accounts', 'totalDebit', 'totalCredit'));
    }
}
