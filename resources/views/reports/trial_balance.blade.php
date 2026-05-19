<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Rekapitulasi Saldo</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ringkasan mutasi debit dan kredit</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('reports.trial_balance.export', ['start_date' => $startDate, 'end_date' => $endDate, 'view' => $view]) }}"
                        class="inline-flex items-center justify-center h-[42px] px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl font-bold text-xs transition-all shadow-sm active:scale-95 uppercase tracking-wide whitespace-nowrap"
                        title="Export tabel ke Excel">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </a>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <form id="trial-balance-filter-form" action="{{ route('reports.trial_balance') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-5 items-end">
                    <input type="hidden" name="view" value="{{ $view }}">
                    
                    <div class="md:col-span-2 lg:col-span-2 xl:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Tampilan Laporan</label>
                        <div class="flex items-center space-x-1 bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-1 rounded-xl h-[45px]">
                            <a href="{{ route('reports.trial_balance', ['view' => 'account', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="flex-1 flex items-center justify-center h-full px-4 text-xs font-bold rounded-lg transition-all {{ $view === 'account' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                                Per Akun
                            </a>
                            <a href="{{ route('reports.trial_balance', ['view' => 'pic', 'start_date' => $startDate, 'end_date' => $endDate]) }}" 
                               class="flex-1 flex items-center justify-center h-full px-4 text-xs font-bold rounded-lg transition-all {{ $view === 'pic' ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-sm border border-slate-200 dark:border-slate-700' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                                Per PIC
                            </a>
                        </div>
                    </div>

                    <div class="w-full">
                        <label for="start_date"
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
                        <input type="date" name="start_date" id="start_date" value="{{ $startDate }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm">
                    </div>

                    <div class="w-full">
                        <label for="end_date"
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                        <input type="date" name="end_date" id="end_date" value="{{ $endDate }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm">
                    </div>

                    <div class="flex items-center gap-2 lg:col-span-4 xl:col-span-1 h-[42px]">
                        <button type="submit"
                            class="flex-1 px-4 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl font-bold text-xs transition-all shadow-md active:scale-95 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>

                        @if(request('start_date') || request('end_date'))
                            <a href="{{ route('reports.trial_balance', ['view' => $view]) }}"
                                class="p-2.5 bg-slate-100 hover:bg-slate-200 text-slate-500 rounded-xl transition-all"
                                title="Reset Filter">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mt-6"
                x-data="{ picSearch: '' }">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
                            @if($view === 'account')
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Kode Akun</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama Akun</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Debit (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Kredit (Rp)</th>
                            </tr>
                            @else
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama PIC</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Total Debit (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Total Kredit (Rp)</th>
                                <th class="w-10 px-4 py-4"></th>
                            </tr>
                            @endif
                        </thead>
                        @if($view === 'account')
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @forelse($accounts as $account)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                        <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                            {{ $account->code }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('reports.general_ledger', [
                                                'account_id' => $account->id,
                                                'start_date' => $startDate,
                                                'end_date' => $endDate,
                                            ]) }}" class="text-slate-800 dark:text-slate-200 font-bold hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                                {{ $account->name }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-300 font-medium">
                                            {{ $account->total_debit > 0 ? number_format($account->total_debit, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-300 font-medium">
                                            {{ $account->total_credit > 0 ? number_format($account->total_credit, 0, ',', '.') : '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                            <span class="mt-2 block text-sm font-medium">Belum Ada Transaksi</span>
                                        </td>
                                    </tr>
                                @endforelse
                        </tbody>
                        @else
                            <tbody class="bg-slate-50/50 dark:bg-slate-900/20 border-b border-slate-100 dark:border-slate-700">
                                <tr>
                                    <td colspan="4" class="px-6 py-3">
                                        <div class="relative max-w-xs">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                                </svg>
                                            </div>
                                            <input type="text" x-model="picSearch" 
                                                placeholder="Cari nama PIC..." 
                                                class="block w-full pl-9 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl bg-white dark:bg-slate-800 text-xs focus:ring-primary-500 focus:border-primary-500 placeholder-slate-400 dark:text-white transition-all">
                                        </div>
                                    </td>
                                </tr>
                            </tbody>

                            @forelse($data as $row)
                                <tbody x-data="{ expanded: false }" 
                                    x-show="'{{ strtolower($row->pic_name) }}'.includes(picSearch.toLowerCase())"
                                    class="border-b-0 group/pic">
                                    <tr @click="expanded = !expanded" 
                                        class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-all cursor-pointer">
                                        <td class="px-6 py-4 text-slate-800 dark:text-slate-200 font-bold">
                                            {{ $row->pic_name }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-300 font-bold">
                                            {{ $row->total_debit > 0 ? number_format($row->total_debit, 0, ',', '.') : '-' }}
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-700 dark:text-slate-300 font-bold">
                                            {{ $row->total_credit > 0 ? number_format($row->total_credit, 0, ',', '.') : '-' }}
                                        </td>
                                            <td class="px-4 py-4 text-center">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-slate-700 flex items-center justify-center text-slate-400 group-hover/pic:text-primary-500 group-hover/pic:bg-primary-50 dark:group-hover/pic:bg-primary-900/30 transition-all shadow-sm">
                                                    <svg :class="expanded ? 'rotate-180' : ''" class="w-5 h-5 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr x-show="expanded" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                            <td colspan="4" class="px-6 pb-6 pt-0">
                                                <div class="bg-slate-50 dark:bg-slate-900/40 rounded-xl border border-slate-200 dark:border-slate-700/50 overflow-hidden shadow-inner">
                                                    <table class="w-full text-[11px]">
                                                        <thead class="bg-slate-100/50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                                                            <tr>
                                                                <th class="px-4 py-2 text-left text-slate-500 uppercase tracking-wider font-black">Akun Penggunaan</th>
                                                                <th class="px-4 py-2 text-right text-slate-500 uppercase tracking-wider font-black">Debit (Rp)</th>
                                                                <th class="px-4 py-2 text-right text-slate-500 uppercase tracking-wider font-black">Kredit (Rp)</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                                                            @foreach($row->details as $detail)
                                                                <tr>
                                                                    <td class="px-4 py-2.5">
                                                                        <span class="font-medium text-slate-500 dark:text-slate-400 mr-2">{{ $detail->code }}</span>
                                                                        <span class="text-slate-700 dark:text-slate-300 font-bold">{{ $detail->name }}</span>
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-400 font-medium">
                                                                        {{ $detail->total_debit > 0 ? number_format($detail->total_debit, 0, ',', '.') : '-' }}
                                                                    </td>
                                                                    <td class="px-4 py-2.5 text-right text-slate-600 dark:text-slate-400 font-medium">
                                                                        {{ $detail->total_credit > 0 ? number_format($detail->total_credit, 0, ',', '.') : '-' }}
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                @empty
                                    <tbody>
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                                <span class="mt-2 block text-sm font-medium">Belum Ada Transaksi</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                @endforelse
                            @endif
                        <tfoot
                            class="bg-slate-50 dark:bg-slate-800/80 border-t-2 border-slate-200 dark:border-slate-700">
                            <tr>
                                <th @if($view === 'account') colspan="2" @endif
                                    class="px-6 py-4 text-right text-sm font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">
                                    Total
                                </th>
                                <th class="px-6 py-4 text-right text-base font-black text-slate-900 dark:text-white">
                                    {{ number_format($totalDebit, 0, ',', '.') }}
                                </th>
                                <th class="px-6 py-4 text-right text-base font-black text-slate-900 dark:text-white">
                                    {{ number_format($totalCredit, 0, ',', '.') }}
                                </th>
                                @if($view === 'pic')
                                    <th class="px-4 py-4"></th>
                                @endif
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            @if($totalDebit === $totalCredit && $totalDebit > 0)
                <div
                    class="mt-4 bg-primary-50 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-800 rounded-xl p-4 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-primary-700 dark:text-primary-400 font-medium">Total Seimbang (Balanced)</span>
                </div>
            @elseif($totalDebit != $totalCredit && $view === 'account')
                <div
                    class="mt-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl p-4 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-red-700 dark:text-red-400 font-medium">Terdapat Selisih: Rp
                        {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}</span>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>