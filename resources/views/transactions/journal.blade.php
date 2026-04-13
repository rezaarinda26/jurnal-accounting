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
                        @if(request('account_id')) <input type="hidden" name="account_id" value="{{ request('account_id') }}"> @endif
                        @if(request('nominal')) <input type="hidden" name="nominal" value="{{ request('nominal') }}"> @endif
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
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm">
                <form action="{{ route('transactions.journal') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1">
                        <label for="pic" class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Cari PIC</label>
                        <input type="text" name="pic" id="pic" value="{{ request('pic') }}" placeholder="Nama PIC..."
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5">
                    </div>
                    <div class="flex-1">
                        <label for="account_id" class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Kode Akun</label>
                        <select name="account_id" id="account_id" class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5">
                            <option value="">Semua Akun</option>
                            @foreach($accounts as $account)
                                <option value="{{ $account->id }}" {{ request('account_id') == $account->id ? 'selected' : '' }}>
                                    {{ $account->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <label for="nominal" class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Nominal Item (Rp)</label>
                        <input type="number" name="nominal" id="nominal" value="{{ request('nominal') }}" placeholder="Contoh: 150000"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5">
                    </div>
                    <div class="w-full md:w-48">
                        <label for="month" class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">Bulan</label>
                        <input type="month" name="month" id="month" value="{{ request('month') }}"
                            class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5">
                    </div>
                    <div class="flex items-end gap-3 w-full md:w-auto">
                        <button type="submit"
                            class="flex-1 md:flex-none px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-primary-500/20 flex items-center justify-center active:scale-95">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Terapkan Filter
                        </button>

                        <button type="submit" formaction="{{ route('transactions.export_excel') }}"
                            class="flex-1 md:flex-none px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-sm transition-all shadow-md shadow-emerald-500/20 flex items-center justify-center active:scale-95" title="Export tabel ke Excel">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Export Excel
                        </button>
                    </div>
                    @if(request()->anyFilled(['pic', 'account_id', 'nominal', 'month']))
                    <div class="flex items-end">
                        <a href="{{ route('transactions.journal') }}" 
                            class="w-full md:w-auto px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold text-sm transition-all text-center flex items-center justify-center">
                            Reset
                        </a>
                    </div>
                    @endif
                </form>
            </div>

            <!-- Transactions Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
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
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-black {{ $journal->bundle->type === 'vendor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400' }} border border-transparent dark:border-slate-700 uppercase tracking-widest">
                                                    {{ $journal->bundle->type }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600 uppercase tracking-wider">
                                                    {{ $journal->bundle->bundle_number }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-bold text-slate-900 dark:text-white">
                                            {{ $journal->journal_number }}
                                        </div>
                                        <div class="text-[11px] text-slate-500 mt-1 font-semibold flex items-center">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            {{ $journal->pic_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        <span class="line-clamp-2 text-xs" title="{{ $journal->description }}">{{ $journal->description ?? '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-black text-slate-800 dark:text-slate-100 text-right text-base">
                                        {{ number_format($journal->entries->filter(fn($e) => $e->is_debit)->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-3 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('transactions.show', $journal->id) }}"
                                                class="p-2 text-slate-400 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/30 rounded-lg transition-all"
                                                title="Lihat Detail">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <form action="{{ route('transactions.destroy', $journal->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus transaksi ini secara permanen?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
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
                                            <svg class="h-16 w-16 text-slate-200 dark:text-slate-700 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <h3 class="text-lg font-bold text-slate-800 dark:text-slate-200">Tidak Ada Transaksi</h3>
                                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 max-w-xs mx-auto">
                                                Gunakan filter di atas untuk mencari transaksi spesifik atau mulai mencatat transaksi baru dalam sebuah bundle.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($journals->hasPages())
                    <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                        {{ $journals->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
