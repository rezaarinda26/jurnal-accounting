<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Jurnal Umum</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Daftar seluruh transaksi kas keluar dari semua bundle (Operasional & Vendor).
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    <form method="GET" action="{{ route('transactions.journal') }}"
                        class="flex items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden h-[42px] transition-all focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500 hover:border-slate-300 dark:hover:border-slate-600">

                        @if(request('pic')) <input type="hidden" name="pic" value="{{ request('pic') }}"> @endif
                        @if(request('account_id')) <input type="hidden" name="account_id"
                        value="{{ request('account_id') }}"> @endif
                        @if(request('nominal')) <input type="hidden" name="nominal" value="{{ request('nominal') }}">
                        @endif
                        @if(request('month')) <input type="hidden" name="month" value="{{ request('month') }}"> @endif

                        <div class="px-3 bg-slate-50 dark:bg-slate-900/50 border-r border-slate-200 dark:border-slate-700 text-slate-500 dark:text-slate-400 flex items-center h-full"
                            title="Urutkan Data">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z">
                                </path>
                            </svg>
                        </div>
                        <select name="sort" onchange="this.form.submit()"
                            class="text-sm border-0 bg-transparent text-slate-700 dark:text-slate-300 py-0 pl-3 pr-8 focus:ring-0 h-full font-semibold cursor-pointer outline-none">
                            <option value="date" {{ $sort === 'date' ? 'selected' : '' }}>Tgl Transaksi</option>
                            <option value="created_at" {{ $sort === 'created_at' ? 'selected' : '' }}>Tgl Input</option>
                        </select>
                        <div class="w-px h-5 bg-slate-200 dark:bg-slate-700"></div>
                        <select name="direction" onchange="this.form.submit()"
                            class="text-sm border-0 bg-transparent text-slate-700 dark:text-slate-300 py-0 pl-3 pr-8 focus:ring-0 h-full font-semibold cursor-pointer outline-none">
                            <option value="desc" {{ $direction === 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ $direction === 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </form>
                </div>
            </div>

            <!-- Search Filters -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm"
                x-data="journalFilters()">
                <form action="{{ route('transactions.journal') }}" method="GET"
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-5 items-end">
                    <div class="md:col-span-2 lg:col-span-2 xl:col-span-1">
                        <label for="pic"
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Cari
                            PIC</label>

                        <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                            <button type="button"
                                @click="open = !open; if(open) $nextTick(() => $refs.picSearchInput.focus())"
                                class="flex items-center justify-between w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5 hover:border-slate-300 dark:hover:border-slate-600">
                                <span x-text="selectedPic || '-- Pilih PIC --'"
                                    :class="!selectedPic ? 'text-slate-400 font-normal outline-none text-sm' : 'text-slate-700 dark:text-white font-semibold text-sm'"></span>
                                <svg :class="open ? 'rotate-180' : ''"
                                    class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <input type="hidden" name="pic" x-model="selectedPic">

                            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[280px]">
                                <div
                                    class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input x-ref="picSearchInput" x-model="search" @keydown.escape="open = false"
                                            placeholder="Cari nama PIC..."
                                            class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                    </div>
                                </div>
                                <ul class="max-h-60 overflow-y-auto py-1">
                                    <li @click="selectedPic = ''; open = false; search = ''"
                                        class="px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer text-xs font-bold text-slate-400 uppercase tracking-widest transition-colors border-b border-slate-50 dark:border-slate-700/50">
                                        -- Semua PIC --
                                    </li>
                                    <template x-for="pic in filteredPics(search)" :key="pic.id">
                                        <li @click="selectedPic = pic.name; open = false; search = ''"
                                            class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group transition-colors">
                                            <span
                                                class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-950 dark:group-hover:text-white transition-colors"
                                                x-text="pic.name"></span>
                                            <div x-show="selectedPic == pic.name"
                                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50">
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2 lg:col-span-2 xl:col-span-1">
                        <label for="account_id"
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Kode
                            Akun</label>

                        <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                            <button type="button"
                                @click="open = !open; if(open) $nextTick(() => $refs.accSearchInput.focus())"
                                class="flex items-center justify-between w-full border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5 hover:border-slate-300 dark:hover:border-slate-600">
                                <span x-text="getAccountName(selectedAccount) || '-- Semua Akun --'"
                                    :class="!selectedAccount ? 'text-slate-400 font-normal outline-none text-sm text-ellipsis overflow-hidden whitespace-nowrap' : 'text-slate-700 dark:text-white font-semibold text-sm text-ellipsis overflow-hidden whitespace-nowrap'"></span>
                                <svg :class="open ? 'rotate-180' : ''"
                                    class="w-4 h-4 text-slate-400 transition-transform duration-200 shrink-0 ml-2"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <input type="hidden" name="account_id" x-model="selectedAccount">

                            <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[320px]">
                                <div
                                    class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input x-ref="accSearchInput" x-model="search" @keydown.escape="open = false"
                                            placeholder="Cari kode atau nama akun..."
                                            class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                    </div>
                                </div>
                                <ul class="max-h-60 overflow-y-auto py-1">
                                    <li @click="selectedAccount = ''; open = false; search = ''"
                                        class="px-4 py-2.5 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer text-xs font-bold text-slate-400 uppercase tracking-widest transition-colors border-b border-slate-50 dark:border-slate-700/50">
                                        -- Semua Akun --
                                    </li>
                                    <template x-for="acc in filteredAccounts(search)" :key="acc.id">
                                        <li @click="selectedAccount = acc.id; open = false; search = ''"
                                            class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group/item transition-colors">
                                            <div class="flex flex-col">
                                                <span
                                                    class="text-[10px] font-black text-slate-400 font-mono tracking-tighter mb-0.5 uppercase"
                                                    x-text="acc.code"></span>
                                                <span
                                                    class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover/item:text-slate-950 dark:group-hover/item:text-white transition-colors"
                                                    x-text="acc.name"></span>
                                            </div>
                                            <div x-show="selectedAccount == acc.id"
                                                class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50">
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="w-full">
                        <label for="nominal"
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Nominal
                            Item (Rp)</label>
                        <input type="number" name="nominal" id="nominal" value="{{ request('nominal') }}"
                            placeholder="Contoh: 150000"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5">
                    </div>
                    <div class="w-full">
                        <label for="month"
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Bulan</label>
                        <input type="month" name="month" id="month" value="{{ request('month') }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5">
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

                        <button type="submit" formaction="{{ route('transactions.export_excel') }}"
                            class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs transition-all shadow-md active:scale-95 flex items-center justify-center uppercase truncate"
                            title="Export tabel ke Excel">
                            Export
                        </button>

                        @if(request()->anyFilled(['pic', 'account_id', 'nominal', 'month']))
                            <a href="{{ route('transactions.journal') }}"
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

            <script>
                function journalFilters() {
                    return {
                        accounts: @json($accounts),
                        pics: @json($pics),
                        selectedAccount: '{{ request('account_id') }}',
                        selectedPic: '{{ request('pic') }}',

                        getAccountName(id) {
                            if (!id) return '';
                            const acc = this.accounts.find(a => a.id == id);
                            return acc ? `${acc.code} - ${acc.name}` : '';
                        },

                        filteredAccounts(search) {
                            if (!search) return this.accounts;
                            const s = search.toLowerCase();
                            return this.accounts.filter(a =>
                                a.name.toLowerCase().includes(s) ||
                                a.code.toLowerCase().includes(s)
                            );
                        },

                        filteredPics(search) {
                            if (!search) return this.pics;
                            const s = search.toLowerCase();
                            return this.pics.filter(p => p.name.toLowerCase().includes(s));
                        }
                    }
                }
            </script>

            <!-- Transactions Table -->
            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div
                    class="px-6 py-4 border-b border-slate-100 dark:border-slate-700/50 flex flex-row items-center gap-3">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Daftar
                        Transaksi</h3>
                    <span
                        class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800 uppercase tracking-widest">
                        {{ $journals->total() }} Item
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-bold tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 font-bold tracking-wider">Bundle / Referensi / PIC</th>
                                <th class="px-6 py-4 font-bold tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 font-bold tracking-wider text-right">Total (Rp)</th>
                                <th class="px-6 py-4 font-bold tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($journals as $journal)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400 font-medium">
                                        {{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="mb-1.5 flex gap-2">
                                            @if($journal->bundle)
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black {{ $journal->bundle->type === 'vendor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' }} border border-transparent dark:border-slate-700 uppercase tracking-widest">
                                                    {{ $journal->bundle->type }}
                                                </span>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 uppercase tracking-wider">
                                                    {{ $journal->bundle->bundle_number }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-bold text-slate-900 dark:text-white">
                                            {{ $journal->journal_number }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-1 font-semibold flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            {{ $journal->pic_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        <span class="line-clamp-2 text-xs"
                                            title="{{ $journal->description }}">{{ $journal->description ?? '—' }}</span>
                                    </td>
                                    <td
                                        class="px-6 py-4 font-black text-slate-800 dark:text-slate-100 text-right text-base">
                                        {{ number_format($journal->entries->filter(fn($e) => $e->is_debit)->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div
                                            class="flex items-center justify-center space-x-3 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('transactions.show', $journal->id) }}"
                                                class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-all"
                                                title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('transactions.destroy', $journal->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus transaksi ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-20 text-center text-slate-400 dark:text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="h-16 w-16 text-slate-200 dark:text-slate-700 mb-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Tidak Ada
                                                Transaksi</h3>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                                                Gunakan filter di atas untuk mencari transaksi spesifik atau mulai mencatat
                                                transaksi baru dalam sebuah bundle.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($journals->hasPages())
                    <div
                        class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        {{ $journals->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>