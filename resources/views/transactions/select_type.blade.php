<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">
                    Pilih Kategori Transaksi
                </h2>
                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 max-w-lg mx-auto">
                    Tentukan kategori transaksi yang ingin Anda kelola.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-2xl mx-auto">
                <!-- Operasional Card -->
                <a href="{{ route('transactions.index', ['type' => 'operasional']) }}"
                    class="group relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:border-primary-500/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    <div
                        class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-slate-50 dark:bg-slate-700/50 rounded-full group-hover:bg-primary-50 dark:group-hover:bg-primary-900/20 group-hover:scale-125 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl flex items-center justify-center mb-4 group-hover:bg-primary-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                </path>
                            </svg>
                        </div>

                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                            Operasional
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-normal mb-4">
                            Biaya operasional harian dan pengeluaran non-PPN.
                        </p>

                        <div
                            class="inline-flex items-center text-[11px] font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400 group-hover:translate-x-1 transition-transform">
                            Pilih Operasional
                            <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </a>

                <!-- Vendor Card -->
                <a href="{{ route('transactions.index', ['type' => 'vendor']) }}"
                    class="group relative bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700 hover:shadow-xl hover:border-blue-500/50 hover:-translate-y-1 transition-all duration-300 overflow-hidden">

                    <div
                        class="absolute top-0 right-0 -mr-6 -mt-6 w-24 h-24 bg-slate-50 dark:bg-slate-700/50 rounded-full group-hover:bg-blue-50 dark:group-hover:bg-blue-900/20 group-hover:scale-125 transition-all duration-500">
                    </div>

                    <div class="relative z-10">
                        <div
                            class="w-12 h-12 bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>
                        </div>

                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white mb-1 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            Vendor (PPN)
                        </h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-normal mb-4">
                            Transaksi pihak ketiga dengan PPN Masukan otomatis.
                        </p>

                        <div
                            class="inline-flex items-center text-[11px] font-bold uppercase tracking-wider text-blue-600 dark:text-blue-400 group-hover:translate-x-1 transition-transform">
                            Pilih Vendor
                            <svg class="w-3 h-3 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                </path>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>

            <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700/50 flex flex-col items-center">
                <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 mb-4">Pilihan Lainnya</p>
                <a href="{{ route('bundles.index') }}"
                    class="inline-flex items-center px-8 py-3 bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 rounded-2xl text-sm font-bold text-slate-600 dark:text-slate-300 hover:border-primary-500 hover:text-primary-600 dark:hover:text-primary-400 hover:shadow-lg hover:-translate-y-0.5 shadow-sm transition-all duration-300 group">
                    <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-primary-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                        </path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Manajemen Bundle
                </a>
            </div>
        </div>
    </div>
</x-app-layout>