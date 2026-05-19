<x-app-layout>
    <div class="py-10" x-data="postingForm()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 px-4 sm:px-0">

            <div class="mb-6">
                <a href="{{ route('payables.index') }}"
                    class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Hutang
                </a>
            </div>

            <div class="mb-6">
                <h2 class="text-3xl font-extrabold text-slate-800 dark:text-white tracking-tight">Verifikasi & Posting Jurnal</h2>
                <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Pilih akun yang sesuai, lalu posting tagihan ini ke Jurnal Umum.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                <!-- LEFT: Tagihan Summary -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Ringkasan Tagihan</h3>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Vendor</p>
                            <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $payable->pic->name ?? 'Vendor Tidak Diketahui' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Keterangan</p>
                            <p class="text-sm text-slate-600 dark:text-slate-300">{{ $payable->description }}</p>
                        </div>
                        @if($payable->invoice_number)
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">No. Invoice</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ $payable->invoice_number }}</p>
                        </div>
                        @endif
                        @if($payable->due_date)
                        <div>
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Jatuh Tempo</p>
                            <p class="text-sm font-semibold text-slate-700 dark:text-slate-200">{{ \Carbon\Carbon::parse($payable->due_date)->format('d M Y') }}</p>
                        </div>
                        @endif
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <p class="text-xs text-slate-400 uppercase tracking-wider mb-0.5">Nominal</p>
                            <p class="text-3xl font-black text-slate-800 dark:text-white">Rp {{ number_format($payable->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Preview Jurnal -->
                    <div class="mt-6 p-4 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Preview Jurnal</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500" x-text="selectedDebitName || '(Akun Debit)'"></span>
                                <span class="font-mono font-semibold text-slate-800 dark:text-white">Rp {{ number_format($payable->amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between pl-6">
                                <span class="text-slate-500" x-text="selectedCreditName || '(Akun Kredit)'"></span>
                                <span class="font-mono font-semibold text-slate-800 dark:text-white">Rp {{ number_format($payable->amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT: Posting Form -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm p-6">
                    <h3 class="text-xs font-bold uppercase tracking-widest text-slate-400 mb-4">Input Akunting</h3>

                    <form method="POST" action="{{ route('payables.post.process', $payable) }}" class="space-y-5">
                        @csrf

                        <!-- Tanggal Jurnal -->
                        <div>
                            <label for="journal_date" class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Tanggal Jurnal <span class="text-red-500">*</span>
                            </label>
                            <input id="journal_date" type="date" name="journal_date"
                                value="{{ old('journal_date', now()->format('Y-m-d')) }}"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm"
                                required>
                            @error('journal_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Akun Debit -->
                        <div x-data="{ open: false, search: '' }" @click.away="open = false" class="relative">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Akun Debit (Beban/Aset) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.debitSearchInput.focus())"
                                class="flex items-center justify-between w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5">
                                <span x-text="selectedDebitName || '-- Pilih atau Cari Akun --'" 
                                      :class="!selectedDebitId ? 'text-slate-400 font-normal outline-none text-sm' : 'text-slate-700 dark:text-white font-semibold text-sm'"></span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <input type="hidden" name="debit_account_id" x-model="selectedDebitId" required>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[320px]">
                                <div class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input x-ref="debitSearchInput" x-model="search" @keydown.escape="open = false" 
                                               placeholder="Cari kode atau nama akun..." 
                                               class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                    </div>
                                </div>
                                <ul class="max-h-60 overflow-y-auto py-1">
                                    <template x-for="account in filteredAccounts(search)" :key="account.id">
                                        <li @click="selectedDebitId = account.id; selectedDebitName = account.code + ' - ' + account.name; open = false; search = ''"
                                            class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group transition-colors">
                                            <div>
                                                <span class="text-[10px] font-bold text-primary-600 dark:text-primary-400 block mb-0.5" x-text="account.code"></span>
                                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-950 dark:group-hover:text-white transition-colors" x-text="account.name"></span>
                                            </div>
                                            <div x-show="selectedDebitId == account.id" class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            @error('debit_account_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Akun Kredit -->
                        <div x-data="{ open: false, search: '' }" @click.away="open = false" class="relative">
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Akun Kredit (Hutang) <span class="text-red-500">*</span>
                            </label>
                            <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.creditSearchInput.focus())"
                                class="flex items-center justify-between w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5">
                                <span x-text="selectedCreditName || '-- Pilih atau Cari Akun --'" 
                                      :class="!selectedCreditId ? 'text-slate-400 font-normal outline-none text-sm' : 'text-slate-700 dark:text-white font-semibold text-sm'"></span>
                                <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <input type="hidden" name="credit_account_id" x-model="selectedCreditId" required>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                 class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[320px]">
                                <div class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input x-ref="creditSearchInput" x-model="search" @keydown.escape="open = false" 
                                               placeholder="Cari kode atau nama akun..." 
                                               class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                    </div>
                                </div>
                                <ul class="max-h-60 overflow-y-auto py-1">
                                    <template x-for="account in filteredAccounts(search)" :key="account.id">
                                        <li @click="selectedCreditId = account.id; selectedCreditName = account.code + ' - ' + account.name; open = false; search = ''"
                                            class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group transition-colors">
                                            <div>
                                                <span class="text-[10px] font-bold text-primary-600 dark:text-primary-400 block mb-0.5" x-text="account.code"></span>
                                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-950 dark:group-hover:text-white transition-colors" x-text="account.name"></span>
                                            </div>
                                            <div x-show="selectedCreditId == account.id" class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                            @error('credit_account_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Catatan Tambahan -->
                        <div>
                            <label for="notes" class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Catatan Tambahan
                                <span class="font-normal normal-case text-slate-400">(opsional)</span>
                            </label>
                            <textarea id="notes" name="notes" rows="2"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm"
                                placeholder="Catatan untuk jurnal ini...">{{ old('notes', $payable->description) }}</textarea>
                        </div>

                        @if($errors->any())
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800/50 rounded-xl p-4">
                                <ul class="text-xs text-red-600 dark:text-red-400 space-y-1">
                                    @foreach($errors->all() as $err)
                                        <li>• {{ $err }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700">
                            <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-md shadow-primary-500/30 active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Posting ke Jurnal Umum
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <script>
        function postingForm() {
            return {
                accounts: @json($accounts),
                selectedDebitId: '{{ old('debit_account_id') }}',
                selectedDebitName: '',
                selectedCreditId: '{{ old('credit_account_id') }}',
                selectedCreditName: '',

                init() {
                    // Pre-select credit account 2-110 (Hutang Usaha) if not set
                    if (!this.selectedCreditId) {
                        const defaultCredit = this.accounts.find(a => a.code.startsWith('2-110'));
                        if (defaultCredit) {
                            this.selectedCreditId = defaultCredit.id;
                            this.selectedCreditName = defaultCredit.code + ' - ' + defaultCredit.name;
                        }
                    } else {
                        const acc = this.accounts.find(a => a.id == this.selectedCreditId);
                        if (acc) this.selectedCreditName = acc.code + ' - ' + acc.name;
                    }

                    if (this.selectedDebitId) {
                        const acc = this.accounts.find(a => a.id == this.selectedDebitId);
                        if (acc) this.selectedDebitName = acc.code + ' - ' + acc.name;
                    }
                },

                filteredAccounts(search) {
                    if (!search) return this.accounts;
                    const s = search.toLowerCase();
                    return this.accounts.filter(a => 
                        a.name.toLowerCase().includes(s) || 
                        a.code.toLowerCase().includes(s)
                    );
                }
            }
        }
    </script>
</x-app-layout>
