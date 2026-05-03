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

    public function exportTrialBalance(Request $request)
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

        $fileName = 'Rekapitulasi_Saldo_' . date('Ymd_His') . '.xlsx';

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
}
