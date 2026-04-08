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
                            <x-input-label for="pic_name" :value="__('Nama Penanggung Jawab (PIC)')"
                                class="font-medium text-slate-600 dark:text-slate-300" />
                            <input id="pic_name"
                                class="block w-full mt-2 border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 dark:bg-slate-900 rounded-lg shadow-sm py-2.5"
                                type="text" name="pic_name" value="{{ old('pic_name') }}"
                                placeholder="Contoh: Budi Santoso" required autofocus />
                        </div>
                        <div class="md:col-span-2">
                            <x-input-label for="description" :value="__('Keterangan Transaksi')"
                                class="font-medium text-slate-600 dark:text-slate-300" />
                            <input id="description"
                                class="block w-full mt-2 border-slate-300 dark:border-slate-600/50 bg-slate-50 dark:bg-slate-900/50 rounded-lg shadow-sm text-slate-500 py-2.5 cursor-not-allowed"
                                type="text" name="description" value="{{ old('description', 'Beban Operasional') }}"
                                readonly />
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
                                <label class="relative inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" :checked="activeTab === 'vendor'"
                                        @change="setTab($event.target.checked ? 'vendor' : 'operasional')"
                                        class="sr-only peer">
                                    <div
                                        class="w-11 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-slate-600 peer-checked:bg-primary-600">
                                    </div>
                                    <span
                                        class="ms-3 text-sm font-bold text-slate-600 dark:text-slate-400 group-hover:text-primary-600 transition-colors">Automasi
                                        PPN</span>
                                </label>
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
                            <div :class="entry.is_ppn ? 'bg-blue-50/50 dark:bg-blue-900/10 border-blue-200 dark:border-blue-800/50 ml-8 border-dashed' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-700 hover:shadow shadow-sm transition-shadow'"
                                class="border p-5 rounded-xl flex flex-col xl:flex-row gap-4 relative group">
                                <div class="w-full xl:w-1/3">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Kode
                                        Akun <span x-show="entry.is_ppn"
                                            class="text-blue-500 font-bold ml-1">(Pajak)</span></label>
                                    <select x-model="entry.account_id" x-bind:name="'entries['+index+'][account_id]'"
                                        :class="entry.is_ppn ? 'bg-slate-100 dark:bg-slate-800 pointer-events-none opacity-80' : 'bg-white dark:bg-slate-900'"
                                        class="block w-full border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-sm py-2.5"
                                        required>
                                        <option value="">-- Pilih Akun --</option>
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                        <option value="18" x-show="entry.is_ppn">PPN Masukan</option>
                                    </select>
                                </div>
                                <div class="w-full xl:w-2/5">
                                    <label
                                        class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Catatan
                                        Item (Opsional)</label>
                                    <input x-model="entry.description" x-bind:name="'entries['+index+'][description]'"
                                        x-bind:readonly="entry.is_ppn"
                                        :class="entry.is_ppn ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed opacity-80' : 'bg-white dark:bg-slate-900'"
                                        class="block w-full border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-sm py-2.5"
                                        type="text" :placeholder="entry.is_ppn ? '' : 'Detail keperluan...'" />
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
                                            x-bind:name="'entries['+index+'][amount]'" x-bind:readonly="entry.is_ppn"
                                            :class="entry.is_ppn ? 'bg-slate-100 dark:bg-slate-800 cursor-not-allowed text-blue-600 dark:text-blue-400 opacity-80' : 'bg-white dark:bg-slate-900 text-slate-800 dark:text-white font-bold'"
                                            class="block w-full pl-9 border-slate-300 dark:border-slate-600 focus:border-primary-500 focus:ring-primary-500 rounded-lg shadow-sm text-sm py-2.5"
                                            type="number" min="0" step="1" required />
                                    </div>
                                </div>
                                <button type="button" @click="removeEntry(index)" x-show="!entry.is_ppn"
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
                        parent_id: null
                    }
                ],
                activeTab: '{{ $openBundle->type }}',
                ppnAccountId: 18,
                totalAmount: 0,

                init() {
                    if (this.activeTab === 'vendor') {
                        // For the initial entry, we need to add its PPN row
                        const firstEntry = this.entries[0];
                        this.entries.push({
                            id: firstEntry.id + 1,
                            account_id: this.ppnAccountId,
                            description: 'PPN (11%)',
                            amount: 0,
                            is_ppn: true,
                            parent_id: firstEntry.id
                        });
                    }
                    this.calculateTotal();
                },

                setTab(tab) {
                    const oldTab = this.activeTab;
                    this.activeTab = tab;

                    if (oldTab === 'operasional' && tab === 'vendor') {
                        // Switching to vendor: add PPN rows for all existing expense entries
                        const newEntries = [];
                        this.entries.forEach(entry => {
                            newEntries.push(entry);
                            if (!entry.is_ppn) {
                                newEntries.push({
                                    id: Date.now() + Math.random(),
                                    account_id: this.ppnAccountId,
                                    description: 'PPN (11%)',
                                    amount: Math.round(entry.amount * 0.11),
                                    is_ppn: true,
                                    parent_id: entry.id
                                });
                            }
                        });
                        this.entries = newEntries;
                    } else if (oldTab === 'vendor' && tab === 'operasional') {
                        // Switching to operasional: remove all PPN rows
                        this.entries = this.entries.filter(entry => !entry.is_ppn);
                    }
                    this.calculateTotal();
                },

                addEntry() {
                    const mainId = Date.now();
                    const newMainEntry = {
                        id: mainId,
                        account_id: '',
                        description: '',
                        amount: 0,
                        is_ppn: false,
                        parent_id: null
                    };

                    this.entries.push(newMainEntry);

                    if (this.activeTab === 'vendor') {
                        this.entries.push({
                            id: mainId + 1,
                            account_id: this.ppnAccountId,
                            description: 'PPN (11%)',
                            amount: 0,
                            is_ppn: true,
                            parent_id: mainId
                        });
                    }
                },

                removeEntry(index) {
                    const entryToRemove = this.entries[index];
                    if (!entryToRemove.is_ppn) {
                        // If it's a parent, remove it and its PPN child
                        this.entries = this.entries.filter(e => e.id !== entryToRemove.id && e.parent_id !== entryToRemove.id);
                    } else {
                        // If it's a PPN row (shouldn't really happen with current UI), just remove it
                        this.entries.splice(index, 1);
                    }

                    if (this.entries.length === 0) {
                        this.addEntry();
                    }

                    this.calculateTotal();
                },

                onAmountChange(entry) {
                    if (!entry.is_ppn && this.activeTab === 'vendor') {
                        // Find associated PPN and update it
                        const ppnRow = this.entries.find(e => e.parent_id === entry.id);
                        if (ppnRow) {
                            ppnRow.amount = Math.round(entry.amount * 0.11);
                        }
                    }
                    this.calculateTotal();
                },

                calculateTotal() {
                    this.totalAmount = this.entries.reduce((sum, entry) => {
                        return sum + (parseFloat(entry.amount) || 0);
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