<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">

            <!-- Page Header -->
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Daftar Hutang</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola dan verifikasi tagihan yang belum dibayar</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('payables.create') }}"
                        class="inline-flex items-center px-4 py-2.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium text-sm transition-all shadow-md shadow-primary-500/20 active:scale-95 duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Tagihan
                    </a>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-amber-50 dark:bg-amber-900/20 flex items-center justify-center text-amber-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Pending</p>
                        <h4 class="text-xl font-bold text-amber-600 dark:text-amber-400 mt-0.5">{{ $countPending }} Tagihan</h4>
                        <p class="text-xs text-slate-400 mt-0.5">Rp {{ number_format($totalPending, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-900/20 flex items-center justify-center text-emerald-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sudah Dijurnal</p>
                        <h4 class="text-xl font-bold text-emerald-600 dark:text-emerald-400 mt-0.5">Rp {{ number_format($totalPosted, 0, ',', '.') }}</h4>
                    </div>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-900/20 flex items-center justify-center text-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Semua</p>
                        <h4 class="text-xl font-bold text-slate-800 dark:text-white mt-0.5">Rp {{ number_format($totalPending + $totalPosted, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>

            <!-- Filter -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm">
                <form method="GET" action="{{ route('payables.index') }}" class="flex items-center gap-3 flex-wrap">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Filter Status:</label>
                    @foreach([''=>'Semua', 'pending'=>'Pending', 'posted'=>'Dijurnal'] as $val => $label)
                        <a href="{{ route('payables.index', array_merge(request()->query(), ['status' => $val])) }}"
                            class="px-4 py-1.5 rounded-full text-xs font-bold border transition-all {{ $status === $val ? 'bg-slate-900 text-white border-slate-900 dark:bg-white dark:text-slate-900' : 'bg-white dark:bg-slate-700 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-600 hover:border-slate-400' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </form>
            </div>

            <!-- Flash Messages -->
            @if(session('success'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 p-4 rounded-r-lg flex items-center">
                    <svg class="w-5 h-5 text-emerald-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-emerald-800 dark:text-emerald-200 font-medium">{{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-4 rounded-r-lg flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-sm text-red-800 dark:text-red-200 font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Table -->
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-700 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Vendor / Keterangan</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">No. Invoice</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Jatuh Tempo</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-right">Nominal (Rp)</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Status</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($payables as $payable)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                                    <td class="px-6 py-4">
                                        <p class="font-semibold text-slate-800 dark:text-white">{{ $payable->pic->name ?? 'Vendor Tidak Diketahui' }}</p>
                                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 truncate max-w-xs">{{ $payable->description }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 dark:text-slate-400 whitespace-nowrap">
                                        {{ $payable->invoice_number ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($payable->due_date)
                                            @php $isOverdue = $payable->status === 'pending' && \Carbon\Carbon::parse($payable->due_date)->isPast(); @endphp
                                            <span class="text-sm {{ $isOverdue ? 'text-red-600 font-bold' : 'text-slate-600 dark:text-slate-300' }}">
                                                {{ \Carbon\Carbon::parse($payable->due_date)->format('d M Y') }}
                                                @if($isOverdue) <span class="ml-1 text-xs font-normal">(Lewat)</span> @endif
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-slate-800 dark:text-slate-100 text-base whitespace-nowrap">
                                        {{ number_format($payable->amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($payable->status === 'pending')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 uppercase tracking-wider">Pending</span>
                                        @elseif($payable->status === 'posted')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 uppercase tracking-wider">Dijurnal</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 uppercase tracking-wider">Lunas</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <div class="flex items-center justify-center gap-2">
                                            @if($payable->status === 'pending')
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('payables.post.show', $payable) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold text-xs transition-all active:scale-95">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Posting
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-500 dark:bg-slate-700 dark:text-slate-400">
                                                        Menunggu Verifikasi
                                                    </span>
                                                @endif

                                                {{-- Delete Button for Pending --}}
                                                <form action="{{ route('payables.destroy', $payable) }}" method="POST" 
                                                    onsubmit="return confirm('Hapus data tagihan ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition-all" title="Hapus Tagihan">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @elseif($payable->status === 'posted')
                                                @if(auth()->user()->isAdmin())
                                                    <a href="{{ route('payables.pay.show', $payable) }}"
                                                        class="inline-flex items-center px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-bold text-xs transition-all active:scale-95 shadow-sm shadow-emerald-500/20">
                                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                        Bayar
                                                    </a>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                                                        Siap Bayar
                                                    </span>
                                                @endif
                                            @elseif($payable->status === 'paid')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase tracking-widest">
                                                    Lunas
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center text-slate-400">
                                        <svg class="w-12 h-12 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"></path></svg>
                                        <p class="font-medium">Tidak ada tagihan ditemukan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($payables->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700">
                        {{ $payables->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
