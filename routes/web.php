<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\PicController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BundleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('pics', PicController::class);
    Route::get('reports/trial-balance', [ReportController::class, 'trialBalance'])->name('reports.trial_balance');
    Route::get('reports/trial-balance/export', [ReportController::class, 'exportTrialBalance'])->name('reports.trial_balance.export');
    Route::get('transactions/search', [TransactionController::class, 'search'])->name('transactions.search');
    Route::get('transactions/export-excel', [TransactionController::class, 'exportExcel'])->name('transactions.export_excel');
    Route::get('transactions/journal', [TransactionController::class, 'journal'])->name('transactions.journal');
    Route::get('transactions/{transaction}/print', [TransactionController::class, 'printVoucher'])->name('transactions.print');
    Route::resource('transactions', TransactionController::class);

    Route::get('bundles', [BundleController::class, 'index'])->name('bundles.index');
    Route::post('bundles', [BundleController::class, 'store'])->name('bundles.store');
    Route::patch('bundles/{bundle}/close', [BundleController::class, 'close'])->name('bundles.close');
    Route::patch('bundles/{bundle}/reopen', [BundleController::class, 'reopen'])->name('bundles.reopen');
    Route::get('bundles/{bundle}/print-vouchers', [BundleController::class, 'printVouchers'])->name('bundles.print_vouchers');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
