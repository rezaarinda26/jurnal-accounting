<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">

            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-slate-800 dark:text-white tracking-tight flex items-center gap-3">
                        <a href="{{ route('transactions.index', ['type' => $bundle->type]) }}" class="text-slate-400 hover:text-primary-500 transition-colors" title="Kembali ke Daftar Bundle">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        </a>
                        <span>Bundle {{ $bundle->bundle_number }}</span>
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400 ml-9">
                        Daftar transaksi kas keluar pada bundle ini.
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex flex-wrap gap-3 items-center">
                    <form method="GET" action="{{ route('transactions.index') }}"
                        class="flex items-center bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm overflow-hidden h-[42px] transition-all focus-within:ring-2 focus-within:ring-primary-500/20 focus-within:border-primary-500 hover:border-slate-300 dark:hover:border-slate-600">
                        
                        <!-- Keep bundle_id parameter -->
                        <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">

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
                    
                    @if($bundle->status === 'open')
                    <a href="{{ route('transactions.create', ['bundle_id' => $bundle->id]) }}"
                        class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium text-sm transition-all shadow-sm shadow-primary-500/20 active:scale-95 duration-200 h-[42px]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Catat Baru
                    </a>
                    @endif
                </div>
            </div>

            @if(session('success'))
                <div class="bg-primary-50 dark:bg-primary-900/30 border-l-4 border-primary-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <p class="text-sm text-primary-800 dark:text-primary-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead
                            class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Tanggal</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Bundle / Referensi / PIC</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Total (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($journals as $journal)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                                    <td class="px-6 py-4 whitespace-nowrap text-slate-500 dark:text-slate-400">
                                        {{ \Carbon\Carbon::parse($journal->date)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="mb-1">
                                            @if($journal->bundle)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:border-slate-600 uppercase tracking-wider">
                                                    Bundle {{ $journal->bundle->bundle_number }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200 dark:bg-amber-900/30 dark:text-amber-400 dark:border-amber-800/50 uppercase tracking-wider">
                                                    No Bundle
                                                </span>
                                            @endif
                                        </div>
                                        <div class="font-medium text-slate-900 dark:text-white">
                                            {{ $journal->journal_number }}</div>
                                        <div
                                            class="text-xs text-primary-600 dark:text-primary-400 mt-1 font-semibold flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            {{ $journal->pic_name }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        <span class="line-clamp-2"
                                            title="{{ $journal->description }}">{{ $journal->description ?? '—' }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100 text-right">
                                        {{ number_format($journal->entries->where('is_debit', true)->sum('amount'), 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-3 opacity-100 md:opacity-0 group-hover:opacity-100 transition-opacity">
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
                                                onsubmit="return confirm('Hapus transaksi? Total laporan akan berubah signifikan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all"
                                                    title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        <span class="mt-2 block text-sm font-medium">Belum ada transaksi kas keluar.</span>
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
