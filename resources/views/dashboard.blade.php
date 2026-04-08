<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 sm:px-0">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Beranda</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ikhtisar harian sistem kas keluar Anda.</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('transactions.create') }}" class="inline-flex items-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium text-sm transition-all shadow-md shadow-primary-500/20 active:scale-95 duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Catat Transaksi
                    </a>
                </div>
            </div>

            <!-- Statistic Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 sm:px-0">
                <!-- Main Metric -->
                <div class="col-span-1 md:col-span-2 relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm hover:shadow-md transition-shadow duration-300 group">
                    <div class="absolute -right-8 -top-8 w-48 h-48 bg-red-50 dark:bg-red-900/10 rounded-full blur-3xl group-hover:bg-red-100 dark:group-hover:bg-red-900/30 transition-colors duration-500"></div>
                    
                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div>
                            <div class="flex items-center space-x-2 text-slate-500 dark:text-slate-400 mb-1">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                                <span class="text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300">Total Transaksi Bulan Ini</span>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Periode: 1 {{ date('F Y') }} &mdash; Hari ini</p>
                        </div>
                        <div class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-white truncate tracking-tight">
                            Rp {{ number_format($totalMonth, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Secondary Metric / Shortlink -->
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 dark:from-slate-800 dark:to-black rounded-2xl p-8 text-white shadow-lg flex flex-col justify-between relative overflow-hidden group">
                     <div class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                     <div class="relative z-10">
                         <div class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center mb-5 backdrop-blur-sm">
                             <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                         </div>
                         <h3 class="text-xl font-bold mb-2">Master Kode Akun</h3>
                         <p class="text-slate-300 text-sm leading-relaxed mb-6">Tambahkan kode akun agar pencatatan data rapi & terstruktur.</p>
                     </div>
                     <a href="{{ route('accounts.index') }}" class="relative z-10 inline-flex items-center text-sm font-semibold text-primary-300 hover:text-white transition-colors group-hover:translate-x-1 duration-300">
                         Kelola Kode Akun <span class="ml-1">&rarr;</span>
                     </a>
                </div>
            </div>
            
            <!-- Dashboard Info -->
            <div class="px-4 sm:px-0">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-start space-x-4">
                    <div class="flex-shrink-0 mt-1">
                        <div class="w-10 h-10 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white">Informasi Sistem</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed max-w-2xl">
                            Aplikasi pengeluaran tunai (transaksi kas keluar) yang dirancang untuk kenyamanan pencatatan terpadu. Masukkan seluruh *invoice* dari satu Penanggung Jawab (PIC) ke dalam satu transaksi menggunakan formulir dinamis modern.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
