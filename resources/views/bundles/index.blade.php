<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div>
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-primary-600 dark:text-slate-400 dark:hover:text-primary-400 font-medium mb-3 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white tracking-tight">Manajemen Bundle Transaksi</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Daftar bundle atau map fisik tempat transaksi dikumpulkan.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <form action="{{ route('bundles.store') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-3">
                        @csrf
                        <div class="relative w-full sm:w-48">
                            <select name="type" required class="block w-full border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 rounded-xl shadow-sm text-sm py-2">
                                <option value="operasional">Operasional</option>
                                <option value="vendor">Vendor</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-xl font-medium text-sm transition-all shadow-sm shadow-primary-500/20 active:scale-95 duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Buka Bundle Baru
                        </button>
                    </form>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-primary-50 dark:bg-primary-900/30 border-l-4 border-primary-500 p-4 rounded-r-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-primary-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <p class="text-sm text-primary-800 dark:text-primary-200 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-3 hidden sm:block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
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

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200 dark:bg-slate-800/50 dark:border-slate-700 dark:text-slate-400">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">No. Bundle</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Tipe</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Jml Transaksi</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Tgl Buka</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($bundles as $bundle)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors group">
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-slate-100">
                                    {{ $bundle->bundle_number }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($bundle->type === 'vendor')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400 border border-blue-200 dark:border-blue-800/50">
                                            Vendor
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            Operasional
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($bundle->status === 'open')
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-primary-100 text-primary-700 dark:bg-primary-900/30 dark:text-primary-400 border border-primary-200 dark:border-primary-800/50">
                                            Open
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                            Closed
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-slate-600 dark:text-slate-300">
                                    {{ $bundle->journals_count }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                    {{ $bundle->created_at->format('d M Y') }}
                                </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-3 transition-opacity">
                                            @if($bundle->status === 'open')
                                            <form action="{{ route('bundles.close', $bundle->id) }}" method="POST" onsubmit="return confirm('Anda yakin men-close bundle ini? Anda tidak bisa lagi menambahkan transaksi ke bundle ini.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm px-3 py-1 bg-rose-500 hover:bg-rose-600 border border-transparent text-white dark:bg-rose-600 dark:hover:bg-rose-700 transition-colors rounded-lg shadow-sm" title="Tutup">
                                                    Tutup (Close)
                                                </button>
                                            </form>
                                            @else
                                            <form action="{{ route('bundles.reopen', $bundle->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin membuka kembali bundle ini?');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="text-sm px-3 py-1 bg-primary-600 hover:bg-primary-700 border border-transparent text-white dark:bg-primary-600 dark:hover:bg-primary-700 transition-colors rounded-lg shadow-sm" title="Buka Kembali">
                                                    Buka Kembali
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="mt-2 block text-sm font-medium">Belum ada Bundle yang dibuat. Buka satu untuk mulai mencatat.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
