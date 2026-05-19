<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">
            
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Buku Besar</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Rincian mutasi dan saldo berjalan per akun</p>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" form="ledger-filter-form" formaction="{{ route('reports.general_ledger.export') }}"
                        class="inline-flex items-center justify-center h-[42px] px-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:text-emerald-600 dark:hover:text-emerald-400 hover:border-emerald-200 dark:hover:border-emerald-800 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-xl font-bold text-xs transition-all shadow-sm active:scale-95 uppercase tracking-wide">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Export
                    </button>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <form id="ledger-filter-form" action="{{ route('reports.general_ledger') }}" method="GET" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-6 gap-5 items-end">
                    <!-- Account Searchable Dropdown -->
                    <div class="md:col-span-2 lg:col-span-2 xl:col-span-3" x-data="{ 
                        open: false, 
                        search: '',
                        selectedId: '{{ $accountId }}',
                        selectedName: '{{ $selectedAccount ? ($selectedAccount->code . ' - ' . $selectedAccount->name) : '' }}',
                        accounts: {{ $accounts->map(fn($a) => ['id' => $a->id, 'code' => $a->code, 'name' => $a->name])->toJson() }},
                        filteredAccounts() {
                            if (!this.search) return this.accounts;
                            return this.accounts.filter(a => 
                                a.name.toLowerCase().includes(this.search.toLowerCase()) || 
                                a.code.toLowerCase().includes(this.search.toLowerCase())
                            );
                        }
                    }" @click.away="open = false">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Pilih Kode Akun</label>
                        <div class="relative">
                            <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.accSearch.focus())"
                                class="flex items-center justify-between w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-primary-500/10 hover:border-slate-300 dark:hover:border-slate-600">
                                <span x-text="selectedName || '-- Pilih Akun --'" 
                                    :class="!selectedName ? 'text-slate-400 font-normal text-sm' : 'text-slate-700 dark:text-white font-semibold text-sm'"></span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <input type="hidden" name="account_id" x-model="selectedId">

                            <div x-show="open" x-transition class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[300px]">
                                <div class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                    <input x-ref="accSearch" x-model="search" placeholder="Cari kode atau nama akun..." 
                                        class="w-full border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500 py-2">
                                </div>
                                <ul class="max-h-60 overflow-y-auto py-1">
                                    <template x-for="acc in filteredAccounts()" :key="acc.id">
                                        <li @click="selectedId = acc.id; selectedName = acc.code + ' - ' + acc.name; open = false; search = ''" 
                                            class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center group transition-colors">
                                            <span class="w-16 font-mono text-xs text-slate-400 group-hover:text-primary-600 transition-colors" x-text="acc.code"></span>
                                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-slate-900 dark:group-hover:text-white" x-text="acc.name"></span>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Date Filters -->
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" 
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm">
                    </div>
                    <div class="w-full">
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" 
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm">
                    </div>

                    <!-- Submit & Reset Buttons -->
                    <div class="flex items-center gap-2 lg:col-span-4 xl:col-span-1 h-[42px]">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-slate-900 hover:bg-black text-white rounded-xl font-bold text-xs transition-all shadow-md active:scale-95 flex items-center justify-center">
                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Cari
                        </button>

                        @if(request()->anyFilled(['account_id']) || request('start_date') || request('end_date'))
                            <a href="{{ route('reports.general_ledger') }}"
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

            <!-- Table Section -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Referensi</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Debit (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Kredit (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Saldo (Rp)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @if($selectedAccount)
                                <!-- Opening Balance Row -->
                                <tr class="bg-slate-50/50 dark:bg-slate-900/20 italic">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-slate-400">—</td>
                                    <td class="px-6 py-4 text-slate-500 font-bold">SALDO AWAL</td>
                                    <td class="px-6 py-4 text-right">—</td>
                                    <td class="px-6 py-4 text-right">—</td>
                                    <td class="px-6 py-4 text-right font-black text-slate-700 dark:text-slate-300">
                                        {{ number_format($openingBalance, 0, ',', '.') }}
                                    </td>
                                </tr>

                                @php $currentBalance = $openingBalance; @endphp
                                @forelse($ledgerEntries as $entry)
                                    @php
                                        if ($normalBalance === 'debit') {
                                            $currentBalance += ($entry->is_debit ? $entry->amount : -$entry->amount);
                                        } else {
                                            $currentBalance += ($entry->is_debit ? -$entry->amount : $entry->amount);
                                        }
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                        <td class="px-6 py-4 font-mono text-xs text-slate-600 dark:text-slate-400">
                                            {{ \Carbon\Carbon::parse($entry->journal->date)->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('transactions.show', $entry->journal_id) }}" class="text-primary-600 font-bold hover:underline">
                                                {{ $entry->journal->journal_number }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 max-w-xs">
                                            <div class="truncate" title="{{ $entry->journal->description }}">
                                                {{ $entry->journal->description ?? '—' }}
                                            </div>
                                            <div class="text-[10px] text-slate-400 mt-0.5">PIC: {{ $entry->journal->pic_name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold {{ $entry->is_debit ? 'text-slate-800 dark:text-white' : 'text-slate-300' }}">
                                            {{ $entry->is_debit ? number_format($entry->amount, 0, ',', '.') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold {{ !$entry->is_debit ? 'text-slate-800 dark:text-white' : 'text-slate-300' }}">
                                            {{ !$entry->is_debit ? number_format($entry->amount, 0, ',', '.') : '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-black {{ $currentBalance < 0 ? 'text-red-500' : 'text-slate-900 dark:text-slate-100' }}">
                                            {{ number_format($currentBalance, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-slate-400">
                                            Tidak ada transaksi pada periode terpilih.
                                        </td>
                                    </tr>
                                @endforelse
                                
                                <!-- Ending Balance Footer Row -->
                                <tr class="bg-slate-50 dark:bg-slate-900/50 border-t-2 border-slate-200 dark:border-slate-700">
                                    <td colspan="5" class="px-6 py-4 text-right font-bold text-slate-500 uppercase tracking-wider">Saldo Akhir Per {{ \Carbon\Carbon::parse($endDate)->format('d F Y') }}</td>
                                    <td class="px-6 py-4 text-right font-black text-lg text-primary-600 dark:text-primary-400 bg-primary-50/30 dark:bg-primary-900/20">
                                        {{ number_format($currentBalance, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @else
                                <tr>
                                    <td colspan="6" class="px-6 py-24 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-16 h-16 mb-4 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                            </svg>
                                            <h3 class="text-lg font-bold text-slate-600 dark:text-slate-400">Pilih Akun Terlebih Dahulu</h3>
                                            <p class="text-sm max-w-xs mx-auto mt-1">Gunakan filter di atas untuk melihat rincian mutasi akun spesifik.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
