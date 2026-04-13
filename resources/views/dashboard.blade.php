<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 px-4 sm:px-0">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Beranda</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Ikhtisar harian sistem kas keluar Anda.
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('transactions.create') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium text-sm transition-all shadow-md shadow-primary-500/20 active:scale-95 duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        Catat Transaksi
                    </a>
                </div>
            </div>

            <!-- Statistic Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 px-4 sm:px-0">
                <!-- Main Metric -->
                <div
                    class="col-span-1 md:col-span-2 relative overflow-hidden bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-8 shadow-sm hover:shadow-md transition-shadow duration-300 group">
                    <div
                        class="absolute -right-8 -top-8 w-48 h-48 bg-red-50 dark:bg-red-900/10 rounded-full blur-3xl group-hover:bg-red-100 dark:group-hover:bg-red-900/30 transition-colors duration-500">
                    </div>

                    <div class="relative z-10 flex flex-col h-full justify-between gap-6">
                        <div>
                            <div class="flex items-center space-x-2 text-slate-500 dark:text-slate-400 mb-1">
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                </svg>
                                <span
                                    class="text-xs font-bold uppercase tracking-widest text-slate-600 dark:text-slate-300">Total
                                    Transaksi Bulan Ini</span>
                            </div>
                            <p class="text-xs text-slate-400 dark:text-slate-500 mt-1">Periode: 1 {{ date('F Y') }}
                                &mdash; Hari ini</p>
                        </div>
                        <div
                            class="text-4xl md:text-5xl lg:text-6xl font-black text-slate-800 dark:text-white truncate tracking-tight">
                            Rp {{ number_format($totalMonth, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                <!-- Secondary Metric / Shortlink -->
                <div
                    class="bg-gradient-to-br from-slate-800 to-slate-900 dark:from-slate-800 dark:to-black rounded-2xl p-8 text-white shadow-lg flex flex-col justify-between relative overflow-hidden group">
                    <div
                        class="absolute inset-0 bg-white/5 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <div class="relative z-10">
                        <div
                            class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center mb-5 backdrop-blur-sm">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold mb-2">Master Kode Akun</h3>
                        <p class="text-slate-300 text-sm leading-relaxed mb-6">Tambahkan kode akun agar pencatatan data
                            rapi & terstruktur.</p>
                    </div>
                    <a href="{{ route('accounts.index') }}"
                        class="relative z-10 inline-flex items-center text-sm font-semibold text-primary-300 hover:text-white transition-colors group-hover:translate-x-1 duration-300">
                        Kelola Kode Akun <span class="ml-1">&rarr;</span>
                    </a>
                </div>
            </div>

            <!-- Secondary Stats Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 px-4 sm:px-0">
                <!-- Stat 1: Transaksi Bulan Ini -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-4 group cursor-pointer" onclick="window.location.href='{{ route('transactions.journal') }}'">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-600 dark:text-blue-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-blue-500 transition-colors">Transaksi Bulan Ini</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalJournalsThisMonth }}</h4>
                    </div>
                </div>

                <!-- Stat 2: Bundle Aktif -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-4 group cursor-pointer" onclick="window.location.href='{{ route('bundles.index') }}'">
                    <div class="w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-600 dark:text-amber-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-amber-500 transition-colors">Bundle Aktif</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $activeBundles }}</h4>
                    </div>
                </div>

                <!-- Stat 3: Total Kode Akun -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-4 group cursor-pointer" onclick="window.location.href='{{ route('accounts.index') }}'">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider group-hover:text-emerald-500 transition-colors">Total Kode Akun</p>
                        <h4 class="text-2xl font-bold text-slate-800 dark:text-white mt-1">{{ $totalAccounts }}</h4>
                    </div>
                </div>

                <!-- Stat 4: Total PPN Masukan -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm hover:shadow-md transition-shadow duration-300 flex items-center space-x-4">
                    <div class="w-12 h-12 rounded-full bg-purple-50 dark:bg-purple-900/20 flex items-center justify-center text-purple-600 dark:text-purple-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">Tax / PPN Masukan</p>
                        <h4 class="text-xl font-bold text-slate-800 dark:text-white mt-1 truncate" title="Rp {{ number_format($totalPpnMasukan, 0, ',', '.') }}">Rp {{ number_format($totalPpnMasukan, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 px-4 sm:px-0">
                <!-- Trend 5 Bulan (Bar Chart) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Trend Pengeluaran</h3>
                        <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Total transaksi dalam 5 bulan terakhir.</p>
                    </div>
                    <div class="p-6 flex-1 min-h-[300px] relative flex items-center justify-center">
                        <canvas id="trendChart" class="w-full max-h-[300px]"></canvas>
                        @if(collect($monthlyTrend)->sum('total') == 0)
                            <div class="absolute inset-0 flex items-center justify-center bg-white/90 dark:bg-slate-800/90 z-10">
                                <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada histori data.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Grafik Kategori (Doughnut) -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col md:flex-row">
                    <!-- Chart Area -->
                    <div class="md:w-1/2 p-6 border-b md:border-b-0 md:border-r border-slate-200 dark:border-slate-700 flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Pengeluaran per Kategori</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Distribusi pengeluaran bulan ini.</p>
                        </div>
                        <div class="flex-1 min-h-[250px] relative flex items-center justify-center">
                            <canvas id="expensesChart" class="w-full max-h-[280px]"></canvas>
                            @if(count($expensesByCategory) === 0)
                                <div class="absolute inset-0 flex items-center justify-center bg-white/90 dark:bg-slate-800/90 z-10">
                                    <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Belum ada data biaya.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <!-- Legend/List Area -->
                    <div class="md:w-1/2 p-6 bg-slate-50 dark:bg-slate-800/50 flex flex-col justify-center">
                        <h4 class="text-sm font-bold text-slate-800 dark:text-white mb-5 uppercase tracking-wider">Top 5 Kategori</h4>
                        <div class="space-y-6">
                            @forelse($expensesByCategory->take(5) as $index => $expense)
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="font-medium text-slate-700 dark:text-slate-300 truncate pr-2" title="{{ $expense['label'] }}">{{ $expense['label'] }}</span>
                                        <span class="font-bold text-slate-900 dark:text-white whitespace-nowrap">Rp {{ number_format($expense['total'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="w-full bg-slate-200 dark:bg-slate-700 rounded-full h-2 overflow-hidden flex">
                                        @php
                                            $colors = ['bg-blue-500', 'bg-amber-500', 'bg-emerald-500', 'bg-red-500', 'bg-violet-500'];
                                            $color = $colors[$index % count($colors)];
                                            $percentage = $totalMonth > 0 ? ($expense['total'] / $totalMonth) * 100 : 0;
                                        @endphp
                                        <div class="{{ $color }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center justify-center h-full text-slate-500 text-sm">Belum ada rincian.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transaksi Terakhir -->
            <div class="px-4 sm:px-0">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-slate-200 dark:border-slate-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Transaksi Terakhir</h3>
                            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">5 jurnal yang terakhir kali dicatat ke dalam sistem.</p>
                        </div>
                        <a href="{{ route('transactions.journal') }}" class="inline-flex items-center text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                            Lihat Semua Jurnal <span class="ml-1">&rarr;</span>
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs text-slate-500 dark:text-slate-400 uppercase bg-slate-50/70 dark:bg-slate-800/50">
                                <tr>
                                    <th class="px-6 py-4 font-semibold">No. Jurnal</th>
                                    <th class="px-6 py-4 font-semibold">Tanggal</th>
                                    <th class="px-6 py-4 font-semibold">PIC</th>
                                    <th class="px-6 py-4 font-semibold">Keterangan</th>
                                    <th class="px-6 py-4 text-right font-semibold">Nominal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($recentJournals as $journal)
                                    @php
                                        $journalAmount = $journal->entries->where('is_debit', true)->sum('amount');
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-slate-900 dark:text-white">{{ $journal->journal_number }}</div>
                                            @if($journal->bundle)
                                                <div class="mt-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300">
                                                        {{ $journal->bundle->bundle_number }}
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 whitespace-nowrap">{{ date('d M Y', strtotime($journal->date)) }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 whitespace-nowrap">{{ $journal->pic_name }}</td>
                                        <td class="px-6 py-4 text-slate-600 dark:text-slate-300 truncate max-w-[200px] xl:max-w-md" title="{{ $journal->description }}">{{ $journal->description }}</td>
                                        <td class="px-6 py-4 text-right font-medium text-slate-900 dark:text-white whitespace-nowrap">Rp {{ number_format($journalAmount, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-10 text-center text-slate-500 dark:text-slate-400">Belum ada transaksi jurnal.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Dashboard Info -->
            <div class="px-4 sm:px-0">
                <div
                    class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex flex-col md:flex-row md:items-start gap-4">
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 rounded-full bg-primary-50 dark:bg-primary-900/20 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-slate-800 dark:text-white">Informasi Sistem</h4>
                        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1 leading-relaxed md:max-w-3xl">
                            SISPENTRA (Sistem Pencatatan Transaksi Kas Terpadu) dirancang untuk kenyamanan pencatatan operasional
                            keuangan. Masukkan seluruh <i>invoice</i> dari satu Penanggung Jawab (PIC) ke dalam satu
                            transaksi menggunakan formulir dinamis modern. Pastikan untuk menutup bundle saat dokumen
                            fisik sudah utuh.
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#cbd5e1' : '#475569';

            const chartData = @json($expensesByCategory);
            
            if (chartData && chartData.length > 0) {
                const ctx = document.getElementById('expensesChart').getContext('2d');
                
                // Extract labels and totals
                const labels = chartData.map(item => item.label);
                const data = chartData.map(item => item.total);
                
                // Color palette
                const backgroundColors = [
                    '#3b82f6', // blue-500
                    '#f59e0b', // amber-500
                    '#10b981', // emerald-500
                    '#ef4444', // red-500
                    '#8b5cf6', // violet-500
                    '#06b6d4', // cyan-500
                    '#f97316', // orange-500
                    '#ec4899', // pink-500
                ];
                
                // Initialize Category Doughnut Chart
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: backgroundColors.slice(0, data.length),
                            borderWidth: 0,
                            borderRadius: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '75%',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: isDarkMode ? '#1e293b' : '#ffffff',
                                titleColor: isDarkMode ? '#f8fafc' : '#0f172a',
                                bodyColor: isDarkMode ? '#cbd5e1' : '#475569',
                                borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                                borderWidth: 1,
                                padding: 12,
                                boxPadding: 6,
                                callbacks: {
                                    label: function(context) {
                                        if (context.parsed !== null) {
                                            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed);
                                        }
                                        return '';
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // Initialize Trend Bar Chart
            const trendDataRaw = @json($monthlyTrend);
            if (trendDataRaw && trendDataRaw.length > 0) {
                const ctxTrend = document.getElementById('trendChart').getContext('2d');
                new Chart(ctxTrend, {
                    type: 'bar',
                    data: {
                        labels: trendDataRaw.map(item => item.label),
                        datasets: [{
                            label: 'Total Pengeluaran',
                            data: trendDataRaw.map(item => item.total),
                            backgroundColor: '#3b82f6', // primary-500
                            hoverBackgroundColor: '#2563eb', // primary-600
                            borderRadius: 6,
                            barPercentage: 0.6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDarkMode ? '#1e293b' : '#ffffff',
                                titleColor: isDarkMode ? '#f8fafc' : '#0f172a',
                                bodyColor: isDarkMode ? '#cbd5e1' : '#475569',
                                borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    label: function(context) {
                                        let label = '';
                                        if (context.parsed.y !== null) {
                                            label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: { color: isDarkMode ? '#334155' : '#f1f5f9' },
                                ticks: { 
                                    color: textColor,
                                    font: { family: "'Inter', sans-serif", size: 11 },
                                    callback: function(value) {
                                        if (value >= 1000000) return 'Rp ' + (value / 1000000).toFixed(1) + ' Jt';
                                        if (value >= 1000) return 'Rp ' + (value / 1000).toFixed(0) + ' Rb';
                                        return 'Rp ' + value;
                                    }
                                }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: textColor, font: { family: "'Inter', sans-serif" } }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>