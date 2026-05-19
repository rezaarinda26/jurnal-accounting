<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 px-4">
            <!-- Header -->
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Pelunasan Hutang
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                        Proses pengeluaran kas untuk membayar tagihan vendor.
                    </p>
                </div>
                <a href="{{ route('payables.index') }}"
                    class="p-2 text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </a>
            </div>

            <form action="{{ route('payables.pay.process', $payable) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Bill Summary Card -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden relative group">


                    <div class="relative flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="space-y-1">
                            <span
                                class="text-[10px] font-black text-primary-600 dark:text-primary-400 uppercase tracking-widest bg-primary-50 dark:bg-primary-900/30 px-2 py-1 rounded-md">Ringkasan
                                Tagihan</span>
                            <h3 class="text-xl font-bold text-slate-800 dark:text-white mt-2">{{ $payable->pic->name }}
                            </h3>
                            <p class="text-sm text-slate-500">{{ $payable->description }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Bayar</p>
                            <p class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">Rp
                                {{ number_format($payable->amount, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div
                        class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700 flex flex-wrap gap-4 text-xs font-semibold">
                        <div class="flex items-center text-slate-500">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                </path>
                            </svg>
                            INV: {{ $payable->invoice_number ?? '-' }}
                        </div>
                        <div class="flex items-center text-slate-500">
                            <svg class="w-4 h-4 mr-1.5 text-slate-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            Tempo:
                            {{ $payable->due_date ? \Carbon\Carbon::parse($payable->due_date)->format('d M Y') : '-' }}
                        </div>
                    </div>
                </div>

                <!-- Payment Details Card -->
                <div
                    class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Select Bundle -->
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                Masukkan ke Bundle <span class="text-red-500">*</span>
                            </label>
                            <select name="bundle_id" required
                                class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-3 shadow-sm">
                                <option value="" disabled selected>-- Pilih Bundle Aktif --</option>
                                @foreach($bundles as $bundle)
                                    <option value="{{ $bundle->id }}">
                                        [{{ strtoupper($bundle->type) }}] {{ $bundle->bundle_number }} -
                                        {{ $bundle->journals_count ?? 0 }} Transaksi
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-[10px] text-slate-400 italic">Hanya bundle yang masih "Open" yang muncul di
                                sini.</p>
                        </div>

                        <!-- Payment Date -->
                        <div class="space-y-2">
                            <label
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                                Tanggal Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                                class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-3 shadow-sm">
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="space-y-2">
                        <label
                            class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">
                            Keterangan<span class="font-normal normal-case text-slate-400"></span>
                        </label>
                        <textarea name="notes" rows="2" placeholder="Contoh: Pelunasan via Transfer Bank Mandiri"
                            class="w-full rounded-2xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm p-4 shadow-sm">Pembayaran Hutang Usaha</textarea>
                    </div>

                    <!-- Accounting Hint -->
                    <div
                        class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl border border-slate-100 dark:border-slate-800 flex gap-4">
                        <div class="p-2 bg-white dark:bg-slate-800 rounded-xl shadow-sm self-start">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700 dark:text-slate-200 mb-0.5">Otomatisasi Jurnal
                                Umum</p>
                            <p class="text-[11px] text-slate-500 leading-relaxed">
                                Sistem akan mendebit akun <span
                                    class="font-bold text-slate-700 dark:text-slate-300">Hutang Usaha (2-110)</span> dan
                                mengkredit akun <span class="font-bold text-slate-700 dark:text-slate-300">Kas
                                    (1-110)</span> senilai nominal tagihan ini.
                            </p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-4 pt-4">
                        <button type="submit"
                            class="flex-1 bg-primary-600 hover:bg-primary-700 text-white rounded-2xl font-bold text-sm py-4 transition-all shadow-lg shadow-primary-500/20 active:scale-[0.98]">
                            Konfirmasi Pembayaran
                        </button>
                        <a href="{{ route('payables.index') }}"
                            class="px-8 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-2xl font-bold text-sm py-4 transition-all active:scale-[0.98]">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>