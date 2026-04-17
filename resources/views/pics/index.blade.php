<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6 px-4 sm:px-0">

            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Master Data</h2>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Kelola kategori akun dan PIC.</p>
                </div>
                <div
                    class="flex items-center space-x-1 bg-slate-200/50 dark:bg-slate-900/50 p-1 rounded-xl shadow-inner">
                    <a href="{{ route('accounts.index') }}"
                        class="px-5 py-2 text-sm font-bold rounded-lg transition-all {{ request()->routeIs('accounts.*') ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-md' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                        Daftar Akun
                    </a>
                    <a href="{{ route('pics.index') }}"
                        class="px-5 py-2 text-sm font-bold rounded-lg transition-all {{ request()->routeIs('pics.*') ? 'bg-white dark:bg-slate-800 text-slate-900 dark:text-white shadow-md' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300' }}">
                        Manajemen PIC
                    </a>
                </div>
            </div>

            <div
                class="flex flex-col md:flex-row gap-4 items-stretch md:items-center justify-between bg-white dark:bg-slate-800 p-4 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                <div class="flex items-center flex-1">
                    <div
                        class="w-10 h-10 rounded-xl bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center mr-3 shrink-0">
                        <svg class="w-5 h-5 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800 dark:text-white">Daftar PIC Terdaftar</h3>
                        <p class="text-xs text-slate-500">Total {{ $pics->total() }} PIC aktif</p>
                    </div>
                </div>

                <form action="{{ route('pics.index') }}" method="GET"
                    class="flex items-center gap-2 flex-1 md:max-w-md">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama atau deskripsi..."
                            class="block w-full pl-9 pr-3 py-2 border border-slate-200 dark:border-slate-700 dark:bg-slate-900 rounded-xl text-sm focus:ring-primary-500 focus:border-primary-500">
                    </div>
                    <button type="submit"
                        class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl text-sm font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cari</button>
                    @if(request('search'))
                        <a href="{{ route('pics.index') }}"
                            class="px-4 py-2 text-slate-500 hover:text-slate-700 text-sm font-bold">Reset</a>
                    @endif
                </form>

                <a href="{{ route('pics.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-slate-900 hover:bg-black dark:bg-white dark:hover:bg-slate-100 text-white dark:text-slate-900 rounded-lg font-bold text-xs transition-all shadow-md active:scale-95 shrink-0">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    TAMBAH PIC
                </a>
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
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama PIC</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Catatan / Deskripsi</th>
                                <th class="px-6 py-4 font-semibold tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            @forelse($pics as $pic)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-700/20 transition-colors">
                                    <td class="px-6 py-4 font-medium text-slate-900 dark:text-white">
                                        {{ $pic->name }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300">
                                        {{ $pic->description ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center space-x-3 transition-opacity">
                                            <a href="{{ route('pics.edit', $pic->id) }}"
                                                class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 transition-colors font-medium">Edit</a>
                                            <span class="text-slate-300 dark:text-slate-600">|</span>
                                            <form action="{{ route('pics.destroy', $pic->id) }}" method="POST"
                                                onsubmit="return confirm('Hapus PIC ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                        <svg class="mx-auto h-12 w-12 text-slate-300 dark:text-slate-600" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                                            </path>
                                        </svg>
                                        <span class="mt-2 block text-sm font-medium">Belum ada PIC terdaftar.</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if($pics->hasPages())
                <div
                    class="px-6 py-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm">
                    {{ $pics->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>