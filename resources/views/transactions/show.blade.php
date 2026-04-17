<x-app-layout>
    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4 sm:px-0">

            <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <a href="{{ url()->previous() === route('transactions.journal') ? route('transactions.journal') : route('transactions.index', ['bundle_id' => $journal->bundle_id]) }}"
                        class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors flex items-center mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Detail Transaksi</h2>
                    <p class="text-sm text-slate-500 mt-1">Informasi lengkap transaksi kas keluar terkait PIC
                        {{ $journal->pic_name }}.</p>
                </div>
                <div class="flex items-center text-[14px]">
                    <a href="{{ route('transactions.print', $journal->id) }}" target="_blank"
                        class="inline-flex items-center px-4 py-2 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-600 rounded-xl font-medium text-sm transition-all shadow-sm active:scale-95 duration-200 h-[42px]">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Cetak Voucher
                    </a>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-8">
                <!-- Info Grid -->
                <div
                    class="grid grid-cols-2 md:grid-cols-4 gap-6 p-6 md:p-8 bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700">
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Status</p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-primary-100 text-primary-800 dark:bg-primary-900/30 dark:text-primary-400 transition-colors uppercase tracking-wider">Terbukukan</span>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Bundle Asal</p>
                        @if($journal->bundle)
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 uppercase">
                                Bundle {{ $journal->bundle->bundle_number }}
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                Tanpa Bundle
                            </span>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Tanggal</p>
                        <p class="font-bold text-slate-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($journal->date)->format('d F Y') }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nomor Referensi
                        </p>
                        <p class="font-mono font-medium text-slate-800 dark:text-slate-200">
                            {{ $journal->journal_number }}</p>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Nama PIC</p>
                        <p class="font-bold text-slate-900 dark:text-white">{{ $journal->pic_name }}</p>
                    </div>
                    @if($journal->description)
                        <div class="col-span-2 md:col-span-4 mt-2">
                            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">Keterangan Umum
                            </p>
                            <p class="text-sm text-slate-700 dark:text-slate-300">{{ $journal->description }}</p>
                        </div>
                    @endif
                </div>

                <!-- Receipt Items -->
                <div class="p-6 md:p-8">
                    <h3 class="text-lg font-bold text-slate-800 dark:text-white mb-6 flex items-center">
                        <div
                            class="w-8 h-8 rounded-lg bg-primary-50 dark:bg-primary-900/10 text-primary-600 dark:text-primary-400 flex items-center justify-center mr-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                </path>
                            </svg>
                        </div>
                        Rincian Item Transaksi
                    </h3>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead
                                class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-900/50 border-y border-slate-200 dark:border-slate-700/50">
                                <tr>
                                    <th class="px-6 py-4 font-semibold tracking-wider">Kode Akun</th>
                                    <th class="px-6 py-4 font-semibold tracking-wider">Catatan</th>
                                    <th class="px-6 py-4 font-semibold tracking-wider text-right">Nominal (Rp)</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @forelse($journal->entries->filter(fn($e) => $e->is_debit) as $entry)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-slate-900 dark:text-white">
                                                {{ $entry->account->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $entry->account->code ?? '' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                            {{ $entry->description ?? '—' }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-semibold text-slate-800 dark:text-slate-200">
                                            {{ number_format($entry->amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-slate-400">Tidak ada item
                                            ditemukan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                            <tfoot>
                                <tr class="border-t border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                                    <td colspan="2"
                                        class="px-6 py-5 text-right font-bold text-slate-800 dark:text-slate-300 uppercase text-xs tracking-wider">
                                        Total Kredit Kas:
                                    </td>
                                    <td class="px-6 py-5 text-right">
                                        <span class="text-xl font-black text-rose-600 dark:text-rose-500">Rp
                                            {{ number_format($journal->entries->filter(fn($e) => $e->is_debit)->sum('amount'), 0, ',', '.') }}</span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>