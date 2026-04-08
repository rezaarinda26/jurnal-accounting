<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Rekapitulasi Saldo</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ringkasan mutasi debit dan kredit seluruh kategori akun.</p>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mt-6">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kode Akun</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama Akun</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Debit (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Kredit (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($accounts as $account)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 font-medium">
                                        {{ $account->code }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-800 dark:text-slate-200 font-medium">
                                        {{ $account->name }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-300">
                                        {{ $account->total_debit > 0 ? number_format($account->total_debit, 0, ',', '.') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-300">
                                        {{ $account->total_credit > 0 ? number_format($account->total_credit, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <span class="mt-2 block text-sm font-medium text-slate-800 dark:text-slate-200">Belum Ada Transaksi</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-slate-50 dark:bg-slate-800/80 border-t-2 border-slate-200 dark:border-slate-700">
                            <tr>
                                <th colspan="2" class="px-6 py-4 text-right text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-4 text-right text-base font-bold text-slate-900 dark:text-white">
                                    {{ number_format($totalDebit, 0, ',', '.') }}
                                </th>
                                <th class="px-6 py-4 text-right text-base font-bold text-slate-900 dark:text-white">
                                    {{ number_format($totalCredit, 0, ',', '.') }}
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            @if($totalDebit === $totalCredit && $totalDebit > 0)
                <div class="mt-4 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800 rounded-xl p-4 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-primary-700 dark:text-primary-400 font-medium">Total Seimbang (Balanced)</span>
                </div>
            @elseif($totalDebit != $totalCredit)
                <div class="mt-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="text-red-700 dark:text-red-400 font-medium">Terdapat Selisih: Rp {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}</span>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
