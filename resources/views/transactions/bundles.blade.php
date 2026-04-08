<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 font-medium mb-3 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke Kategori
                    </a>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">
                        Bundle Transaksi {{ ucfirst($type) }}
                    </h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih bundle/map fisik untuk melihat dan mencatat transaksi {{ $type }}.</p>
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

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-3 hidden sm:block" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Terdapat error:</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($bundles as $bundle)
                    <div
                        class="group relative bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 {{ $bundle->status === 'open' ? 'ring-2 ring-primary-500/50' : 'opacity-80 hover:opacity-100' }}">

                        <a href="{{ route('transactions.index', ['bundle_id' => $bundle->id]) }}"
                            class="absolute inset-0 z-10 block cursor-pointer"></a>

                        <div class="flex items-start justify-between mb-4">
                            <div
                                class="p-3 rounded-xl {{ $bundle->status === 'open' ? 'bg-primary-50 text-primary-600 dark:bg-primary-900/30 dark:text-primary-400' : 'bg-slate-50 text-slate-500 dark:bg-slate-700 dark:text-slate-400' }}">
                                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2 6a2 2 0 012-2h5l2 2h5a2 2 0 012 2v6a2 2 0 01-2 2H4a2 2 0 01-2-2V6z"></path>
                                </svg>
                            </div>

                            <div class="relative z-20">
                                @if($bundle->status === 'open')
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-primary-100 text-primary-600 border border-primary-200 dark:bg-primary-900/30 dark:text-primary-400 dark:border-primary-800/50 uppercase tracking-wider">
                                        Open
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600 uppercase tracking-wider">
                                        Closed
                                    </span>
                                @endif
                            </div>
                        </div>

                        <h3
                            class="text-lg font-bold text-slate-800 dark:text-white mb-1 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                            {{ $bundle->bundle_number }}
                        </h3>

                        <div class="flex items-center justify-between text-sm text-slate-500 dark:text-slate-400 mt-4 mb-2">
                            <span class="flex items-center">
                                <svg class="w-4 h-4 mr-1.5 opacity-70" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                {{ $bundle->journals_count }} Transaksi
                            </span>
                        </div>

                        <div
                            class="text-xs text-slate-400 dark:text-slate-500 border-t border-slate-100 dark:border-slate-700/50 pt-3 mt-1 flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <span title="Dibuat">Buat: {{ $bundle->created_at->format('d M y') }}</span>
                                @if($bundle->closed_at)
                                    <span title="Ditutup">Tutup: {{ $bundle->closed_at->format('d M y') }}</span>
                                @else
                                    <span class="text-primary-500 dark:text-primary-400 font-medium">Aktif</span>
                                @endif
                            </div>

                            @if($bundle->status === 'open')
                                <a href="{{ route('transactions.create', ['bundle_id' => $bundle->id]) }}"
                                    class="relative z-20 w-full py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-bold text-center text-xs transition-colors shadow-sm active:scale-95">
                                    Catat Transaksi Baru
                                </a>
                            @endif
                        </div>

                    </div>
                @empty
                    <form action="{{ route('bundles.store') }}" method="POST" class="col-span-full">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <button type="submit"
                            class="w-full py-12 flex flex-col items-center justify-center bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700 hover:border-primary-500 hover:bg-primary-50 dark:hover:border-primary-500/50 dark:hover:bg-primary-900/20 transition-all duration-200 group cursor-pointer focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900">
                            <div
                                class="p-3 rounded-full bg-slate-50 dark:bg-slate-800 group-hover:bg-primary-100 dark:group-hover:bg-primary-900/50 transition-colors mb-4">
                                <svg class="h-8 w-8 text-slate-400 dark:text-slate-500 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <h3
                                class="text-base font-semibold text-slate-800 dark:text-slate-200 group-hover:text-primary-700 dark:group-hover:text-primary-300 mb-1 transition-colors">
                                Belum ada Bundel Transaksi
                            </h3>
                            <p
                                class="text-sm text-slate-500 dark:text-slate-400 group-hover:text-primary-600/80 dark:group-hover:text-primary-400/80 transition-colors">
                                Klik area ini untuk langsung membuat bundle pertama Anda.</p>
                        </button>
                    </form>
                @endforelse
            </div>

        </div>
    </div>
</x-app-layout>
