<x-app-layout>
    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 px-4 sm:px-0">
            <div class="mb-6">
                 <a href="{{ route('accounts.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors flex items-center">
                     <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                     Kembali ke Daftar
                 </a>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden p-8">
                <div class="mb-6 pb-6 border-b border-slate-100 dark:border-slate-700/50">
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Edit Kode Akun</h2>
                    <p class="text-sm text-slate-500 mt-1">Perbarui rincian kategori pengeluaran ini.</p>
                </div>
                
                <form method="POST" action="{{ route('accounts.update', $account->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <x-input-label for="code" :value="__('Kode Kategori (Opsional)')" class="text-slate-600 dark:text-slate-300 font-medium" />
                        <input id="code" class="block mt-2 w-full border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 rounded-lg shadow-sm py-2.5" type="text" name="code" value="{{ old('code', $account->code) }}" placeholder="Contoh: BBN-01" autofocus />
                        <x-input-error :messages="$errors->get('code')" class="mt-2 text-sm" />
                    </div>

                    <div>
                        <x-input-label for="name" :value="__('Nama Kategori / Beban')" class="text-slate-600 dark:text-slate-300 font-medium" />
                        <input id="name" class="block mt-2 w-full border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 rounded-lg shadow-sm py-2.5" type="text" name="name" value="{{ old('name', $account->name) }}" placeholder="Contoh: BBM Kendaraan Operasional" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm" />
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('accounts.index') }}" class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-lg transition-colors focus:ring-2 focus:ring-slate-200">Batal</a>
                        <button type="submit" class="px-6 py-2.5 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-all shadow-sm shadow-primary-500/30 active:scale-95">Simpan Perubahan</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
