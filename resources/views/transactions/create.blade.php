<x-app-layout>
    <div class="py-10" x-data="journalForm()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 sm:px-0">
            <div class="mb-6 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <a href="{{ route('transactions.index', ['bundle_id' => $openBundle->id]) }}"
                        class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors flex items-center mb-3">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                    <h2 class="text-3xl font-bold text-slate-800 dark:text-white tracking-tight">Catat Pengeluaran</h2>
                    <p class="text-sm text-slate-500 mt-1">Formulir dinamis untuk mencatat pengeluaran per PIC.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                    <div class="flex">
                        <svg class="w-5 h-5 text-red-500 mr-3 hidden sm:block" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-red-800">Terdapat error pada input Anda:</h3>
                            <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('transactions.store') }}">
                @csrf
                <input type="hidden" name="bundle_id" value="{{ $openBundle->id }}">

                <div
                    class="bg-white dark:bg-slate-800 rounded-t-2xl border-x border-t border-slate-200 dark:border-slate-700 p-6 md:p-8">

                    <div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center mr-3 text-sm shrink-0">1</span>
                            Informasi Umum
                        </h3>

                        <div class="flex items-center gap-2">
                            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tipe
                                Transaksi:</span>
                            <span
                                class="px-3 py-1 rounded-lg text-xs font-bold uppercase tracking-wide {{ $openBundle->type === 'vendor' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-400' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300' }}">
                                {{ $openBundle->type }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-input-label for="journal_number" :value="__('Nomor Referensi')"
                                class="font-medium text-slate-600 dark:text-slate-300" />
                            <input id="journal_number"
                                class="block w-full mt-2 border-slate-300 dark:border-slate-600/50 bg-slate-50 dark:bg-slate-900/50 rounded-lg shadow-sm text-slate-500 font-mono text-sm py-2.5"
                                type="text" name="journal_number" value="{{ old('journal_number', $newNumber) }}"
                                readonly />
                        </div>
                        <div>
                            <x-input-label for="date" :value="__('Tanggal Transaksi')"
                                class="font-medium text-slate-600 dark:text-slate-300" />
                            <input id="date"
                                class="block w-full mt-2 border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 rounded-lg shadow-sm py-2.5"
                                type="date" name="date" value="{{ old('date', date('Y-m-d')) }}" required />
                        </div>
                        <div class="md:col-span-2">
                            <div class="flex justify-between items-center mb-1.5">
                                <x-input-label for="pic_id" :value="__('Nama Penanggung Jawab (PIC)')"
                                    class="font-medium text-slate-600 dark:text-slate-300" />
                            </div>
                            
                            <div class="relative mt-2" x-data="{ open: false, search: '' }" @click.away="open = false">
                                <button type="button" @click="open = !open; if(open) $nextTick(() => $refs.picSearchInput.focus())"
                                    class="flex items-center justify-between w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 rounded-lg shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5">
                                    <span x-text="selectedPicName || '-- Pilih atau Cari Nama PIC --'" 
                                          :class="!selectedPicId ? 'text-slate-400 font-normal outline-none text-sm' : 'text-slate-700 dark:text-white font-semibold text-sm'"></span>
                                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                
                                <input type="hidden" name="pic_id" x-model="selectedPicId" required>

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
                                            <input x-ref="picSearchInput" x-model="search" @keydown.escape="open = false" 
                                                   placeholder="Cari nama PIC..." 
                                                   class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                        </div>
                                    </div>
                                    <ul class="max-h-60 overflow-y-auto py-1">
                                        <template x-for="pic in filteredPics(search)" :key="pic.id">
                                            <li @click="selectedPicId = pic.id; selectedPicName = pic.name; open = false; search = ''"
                                                class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group transition-colors">
                                                <span class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-950 dark:group-hover:text-white transition-colors" x-text="pic.name"></span>
                                                <div x-show="selectedPicId == pic.id" class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                                            </li>
                                        </template>
                                        <div x-show="filteredPics(search).length === 0" class="px-4 py-12 text-center text-slate-400">
                                            <svg class="mx-auto h-8 w-8 text-slate-200 dark:text-slate-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <p class="text-[11px] font-bold uppercase tracking-widest">PIC Tidak Ditemukan</p>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Keterangan Transaksi')"
                                class="font-medium text-slate-600 dark:text-slate-300" />
                            <input id="description"
                                class="block w-full mt-2 border-slate-300 dark:border-slate-600/50 bg-white dark:bg-slate-900 rounded-lg shadow-sm text-slate-800 dark:text-white py-2.5 focus:border-primary-500 focus:ring focus:ring-primary-500/20 transition-all"
                                type="text" name="description" value="{{ old('description', 'Beban Operasional') }}"
                                placeholder="Contoh: Beban Operasional, Setoran PPh 23, dsb." />
                        </div>
                    </div>
                </div>

                <div
                    class="bg-slate-50 dark:bg-slate-800/80 rounded-b-2xl border border-slate-200 dark:border-slate-700 p-6 md:p-8 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white flex items-center">
                            <span
                                class="w-8 h-8 rounded-lg bg-primary-100 dark:bg-primary-900/30 text-primary-600 dark:text-primary-400 flex items-center justify-center mr-3 text-sm">2</span>
                            Rincian Invoice
                        </h3>
                        <div class="flex items-center gap-4">
                            @if($openBundle->type === 'vendor')
                                <div class="flex items-center gap-6">
                                    <label class="relative inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" :checked="isPpnActive"
                                            @change="togglePpn($event.target.checked)"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-primary-600">
                                        </div>
                                        <span
                                            class="ms-3 text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-primary-600 transition-colors">Automasi
                                            PPN</span>
                                    </label>

                                    <label class="relative inline-flex items-center cursor-pointer group">
                                        <input type="checkbox" :checked="isPphActive"
                                            @change="togglePph($event.target.checked)"
                                            class="sr-only peer">
                                        <div
                                            class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-amber-300 dark:peer-focus:ring-amber-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-amber-600">
                                        </div>
                                        <span
                                            class="ms-3 text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-amber-600 transition-colors">Automasi
                                            PPh 23</span>
                                    </label>
                                </div>
                            @endif

                            <button type="button" @click="addEntry()"
                                class="text-sm font-semibold text-primary-600 hover:text-primary-800 dark:text-primary-400 flex items-center bg-white dark:bg-slate-700 px-4 py-2 rounded-xl focus:ring-2 focus:ring-primary-500 border border-slate-200 dark:border-slate-600 shadow-sm transition-transform active:scale-95">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Tambah Baris
                            </button>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <template x-for="(entry, index) in entries" :key="entry.id">
                            <div :class="{
                                    'bg-blue-50/50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800/50 ml-8 border-dashed': entry.is_ppn,
                                    'bg-amber-50/50 dark:bg-amber-900/10 border-amber-200 dark:border-amber-800/50 ml-8 border-dashed': entry.is_pph,
                                    'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:shadow shadow-sm transition-shadow': !entry.is_ppn && !entry.is_pph
                                }"
                                class="border p-5 rounded-xl flex flex-col xl:flex-row gap-4 relative group">
                                
                                <input type="hidden" x-bind:name="'entries['+index+'][is_debit]'" :value="entry.is_pph ? 'false' : 'true'">

                                <div class="w-full xl:w-1/3">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kode
                                        Akun 
                                        <span x-show="entry.is_ppn" class="text-blue-500 font-bold ml-1">(Pajak PPN)</span>
                                        <span x-show="entry.is_pph" class="text-amber-600 font-bold ml-1">(Potongan PPh 23)</span>
                                    </label>
                                    <div class="relative" x-data="{ open: false, search: '' }" @click.away="open = false">
                                        <button type="button" @click="if(!entry.is_ppn && !entry.is_pph) { open = !open; if(open) $nextTick(() => $refs.searchInput.focus()) }"
                                            :class="(entry.is_ppn || entry.is_pph) ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed opacity-80' : 'bg-white dark:bg-slate-900 border-slate-200 dark:border-slate-700 hover:border-slate-400 dark:hover:border-slate-500 transition-all'"
                                            class="flex items-center justify-between w-full border rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5">
                                            <span x-text="getAccountName(entry.account_id) || '-- Pilih Akun --'" 
                                                  :class="!entry.account_id ? 'text-slate-400 font-normal outline-none' : 'text-slate-700 dark:text-white font-semibold'"></span>
                                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        
                                        <input type="hidden" x-bind:name="'entries['+index+'][account_id]'" x-model="entry.account_id" required>

                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-150"
                                             x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                             class="absolute z-[60] mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[320px]">
                                            <div class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                                <div class="relative">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                        </svg>
                                                    </div>
                                                    <input x-ref="searchInput" x-model="search" @keydown.escape="open = false" 
                                                           placeholder="Cari kode atau nama akun..." 
                                                           class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                                </div>
                                            </div>
                                            <ul class="max-h-72 overflow-y-auto py-1">
                                                <template x-for="acc in filteredAccounts(search)" :key="acc.id">
                                                    <li @click="entry.account_id = acc.id; open = false; search = ''; onAmountChange(entry)"
                                                        class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group/item transition-colors">
                                                        <div class="flex flex-col">
                                                            <span class="text-[10px] font-black text-slate-400 font-mono tracking-tighter mb-0.5 uppercase" x-text="acc.code"></span>
                                                            <span class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover/item:text-slate-950 dark:group-hover/item:text-white transition-colors" x-text="acc.name"></span>
                                                        </div>
                                                        <div x-show="entry.account_id == acc.id" class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50"></div>
                                                    </li>
                                                </template>
                                                <div x-show="filteredAccounts(search).length === 0" class="px-4 py-12 text-center text-slate-400">
                                                    <svg class="mx-auto h-8 w-8 text-slate-200 dark:text-slate-700 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <p class="text-[11px] font-bold uppercase tracking-widest">Tidak Ada Hasil</p>
                                                </div>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-full xl:w-2/5">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Catatan
                                        Item (Opsional)</label>
                                    <input x-model="entry.description" x-bind:name="'entries['+index+'][description]'"
                                        x-bind:readonly="entry.is_ppn || entry.is_pph"
                                        :class="(entry.is_ppn || entry.is_pph) ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed opacity-80' : 'bg-white dark:bg-slate-900'"
                                        class="block w-full border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-sm py-2.5"
                                        type="text" :placeholder="(entry.is_ppn || entry.is_pph) ? '' : 'Detail keperluan...'" />
                                </div>
                                <div class="w-full xl:w-1/4">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Nominal
                                        (Rp)</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-slate-500 sm:text-sm font-medium">Rp</span>
                                        </div>
                                        <input x-model.number="entry.amount" @input="onAmountChange(entry)"
                                            x-bind:name="'entries['+index+'][amount]'" x-bind:readonly="entry.is_ppn || entry.is_pph"
                                            :class="entry.is_ppn ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed text-blue-600 dark:text-blue-400 opacity-80 font-bold' : (entry.is_pph ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed text-amber-600 dark:text-amber-400 opacity-80 font-bold' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold')"
                                            class="block w-full pl-9 border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-sm py-2.5"
                                            type="number" min="0" step="1" required />
                                    </div>
                                </div>
                                <button type="button" @click="removeEntry(index)" x-show="!entry.is_ppn && !entry.is_pph"
                                    class="absolute -top-3 -right-3 bg-white text-red-600 border border-red-100 hover:bg-red-600 hover:text-white dark:bg-slate-700 dark:border-slate-600 dark:text-red-400 dark:hover:bg-red-500 dark:hover:text-white rounded-full p-1.5 shadow-sm transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100"
                                    title="Hapus transaksi">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                        </template>
                    </div>

                    <div
                        class="mt-8 pt-6 border-t border-slate-300/50 dark:border-slate-700 border-dashed flex flex-col md:flex-row md:items-center justify-between gap-6 pb-2">
                        <div
                            class="w-full md:w-auto p-4 bg-white dark:bg-slate-900/50 border border-slate-200 dark:border-slate-700 rounded-xl shadow-sm text-center md:text-left">
                            <div class="text-sm font-semibold text-slate-500 uppercase tracking-wide mb-1">Total Kas
                                Keluar</div>
                            <div class="text-3xl font-black text-rose-600 dark:text-rose-500"
                                x-text="formatCurrency(totalAmount)"></div>
                        </div>
                        <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                            <a href="{{ route('transactions.index', ['bundle_id' => $openBundle->id]) }}"
                                class="flex-1 sm:flex-none text-center px-6 py-3.5 text-sm font-bold text-slate-600 bg-white border border-slate-300 hover:bg-slate-50 rounded-xl transition-colors dark:bg-slate-800 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700">Batal</a>
                            <button type="submit"
                                class="flex-1 sm:flex-none px-10 py-3.5 text-sm font-bold text-white bg-slate-900 hover:bg-black dark:bg-primary-600 dark:hover:bg-primary-500 rounded-xl transition-all shadow-md active:scale-95">Simpan
                                Data Transaksi</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <script>
        function journalForm() {
            return {
                entries: [
                    {
                        id: Date.now(),
                        account_id: '',
                        description: '',
                        amount: 0,
                        is_ppn: false,
                        is_pph: false,
                        parent_id: null
                    }
                ],
                accounts: @json($accounts),
                pics: @json($pics),
                isPpnActive: '{{ $openBundle->type }}' === 'vendor',
                isPphActive: false,
                ppnAccountId: 18,
                pphAccountId: 19,
                totalAmount: 0,
                selectedPicId: '{{ old('pic_id') }}',
                selectedPicName: '',

                getAccountName(id) {
                    if (!id) return '';
                    const acc = this.accounts.find(a => a.id == id);
                    if (acc) return `${acc.code} - ${acc.name}`;
                    if (id == this.ppnAccountId) return '1-115 - PPN Masukan';
                    if (id == this.pphAccountId) return '2-100 - Hutang PPh 23';
                    return 'Akun Tidak Dikenal';
                },

                filteredAccounts(search) {
                    if (!search) return this.accounts;
                    const s = search.toLowerCase();
                    return this.accounts.filter(a => 
                        a.name.toLowerCase().includes(s) || 
                        a.code.toLowerCase().includes(s)
                    );
                },

                filteredPics(search) {
                    if (!search) return this.pics;
                    const s = search.toLowerCase();
                    return this.pics.filter(p => p.name.toLowerCase().includes(s));
                },

                init() {
                    if (this.selectedPicId) {
                        const pic = this.pics.find(p => p.id == this.selectedPicId);
                        if (pic) this.selectedPicName = pic.name;
                    }
                    if (this.isPpnActive) {
                        this.addPpnToEntry(this.entries[0]);
                    }
                    if (this.isPphActive) {
                        this.addPphToEntry(this.entries[0]);
                    }
                    this.calculateTotal();
                },

                togglePpn(active) {
                    this.isPpnActive = active;
                    if (active) {
                        this.entries.forEach(entry => {
                            if (!entry.is_ppn && !entry.is_pph) {
                                this.addPpnToEntry(entry);
                            }
                        });
                    } else {
                        this.entries = this.entries.filter(entry => !entry.is_ppn);
                    }
                    this.calculateTotal();
                },

                togglePph(active) {
                    this.isPphActive = active;
                    if (active) {
                        this.entries.forEach(entry => {
                            if (!entry.is_ppn && !entry.is_pph) {
                                this.addPphToEntry(entry);
                            }
                        });
                    } else {
                        this.entries = this.entries.filter(entry => !entry.is_pph);
                    }
                    this.calculateTotal();
                },

                addPpnToEntry(mainEntry) {
                    // Check if already exists
                    if (this.entries.find(e => e.parent_id === mainEntry.id && e.is_ppn)) return;
                    
                    const index = this.entries.findIndex(e => e.id === mainEntry.id);
                    this.entries.splice(index + 1, 0, {
                        id: 'ppn-' + mainEntry.id,
                        account_id: this.ppnAccountId,
                        description: 'PPN (11%)',
                        amount: Math.round(mainEntry.amount * 0.11),
                        is_ppn: true,
                        is_pph: false,
                        parent_id: mainEntry.id
                    });
                },

                addPphToEntry(mainEntry) {
                    // Check if already exists
                    if (this.entries.find(e => e.parent_id === mainEntry.id && e.is_pph)) return;
                    
                    const index = this.entries.findLastIndex(e => e.id === mainEntry.id || e.parent_id === mainEntry.id);
                    this.entries.splice(index + 1, 0, {
                        id: 'pph-' + mainEntry.id,
                        account_id: this.pphAccountId,
                        description: 'PPh 23 (2%)',
                        amount: Math.round(mainEntry.amount * 0.02),
                        is_ppn: false,
                        is_pph: true,
                        parent_id: mainEntry.id
                    });
                },

                addEntry() {
                    const mainId = Date.now();
                    const newMainEntry = {
                        id: mainId,
                        account_id: '',
                        description: '',
                        amount: 0,
                        is_ppn: false,
                        is_pph: false,
                        parent_id: null
                    };

                    this.entries.push(newMainEntry);

                    if (this.isPpnActive) {
                        this.addPpnToEntry(newMainEntry);
                    }
                    if (this.isPphActive) {
                        this.addPphToEntry(newMainEntry);
                    }
                },

                removeEntry(index) {
                    const entryToRemove = this.entries[index];
                    if (!entryToRemove.is_ppn && !entryToRemove.is_pph) {
                        // If it's a parent, remove it and its children (PPN/PPh)
                        this.entries = this.entries.filter(e => e.id !== entryToRemove.id && e.parent_id !== entryToRemove.id);
                    } else {
                        // If it's a child row, just remove it
                        this.entries.splice(index, 1);
                    }

                    if (this.entries.length === 0) {
                        this.addEntry();
                    }

                    this.calculateTotal();
                },

                onAmountChange(entry) {
                    if (!entry.is_ppn && !entry.is_pph) {
                        // Update PPN if active
                        if (this.isPpnActive) {
                            const ppnRow = this.entries.find(e => e.parent_id === entry.id && e.is_ppn);
                            if (ppnRow) {
                                ppnRow.amount = Math.round(entry.amount * 0.11);
                            }
                        }
                        // Update PPh if active
                        if (this.isPphActive) {
                            const pphRow = this.entries.find(e => e.parent_id === entry.id && e.is_pph);
                            if (pphRow) {
                                pphRow.amount = Math.round(entry.amount * 0.02);
                            }
                        }
                    }
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.totalAmount = this.entries.reduce((sum, entry) => {
                        const amount = parseFloat(entry.amount) || 0;
                        return entry.is_pph ? sum - amount : sum + amount;
                    }, 0);
                },

                formatCurrency(value) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(value);
                }
            }
        }
    </script>
</x-app-layout>