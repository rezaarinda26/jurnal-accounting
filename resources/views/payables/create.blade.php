<x-app-layout>
    <div class="py-10" x-data="payableForm()">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 px-4 sm:px-0">

            <div class="mb-6">
                <a href="{{ route('payables.index') }}"
                    class="text-sm font-medium text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Daftar Hutang
                </a>
            </div>

            <div
                class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden p-8">
                <div class="mb-6">
                    <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Catat Tagihan Baru</h2>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Isi data tagihan/invoice yang diterima
                        dari vendor.</p>
                </div>

                <form method="POST" action="{{ route('payables.store') }}" class="space-y-5">
                    @csrf

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <!-- PIC / Vendor Searchable Dropdown -->
                        <div class="sm:col-span-2">
                            <div class="flex justify-between items-center mb-1.5">
                                <label for="pic_id"
                                    class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                                    Pilih Vendor / PIC <span class="text-red-500">*</span>
                                </label>
                            </div>

                            <div class="relative mt-2" x-data="{ open: false, search: '' }" @click.away="open = false">
                                <button type="button"
                                    @click="open = !open; if(open) $nextTick(() => $refs.picSearchInput.focus())"
                                    class="flex items-center justify-between w-full border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-900 rounded-xl shadow-sm px-4 py-2.5 text-left transition-all focus:ring-4 focus:ring-slate-900/5">
                                    <span x-text="selectedPicName || '-- Pilih atau Cari Nama Vendor --'"
                                        :class="!selectedPicId ? 'text-slate-400 font-normal outline-none text-sm' : 'text-slate-700 dark:text-white font-semibold text-sm'"></span>
                                    <svg :class="open ? 'rotate-180' : ''"
                                        class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>

                                <input type="hidden" name="pic_id" x-model="selectedPicId" required>

                                <div x-show="open" x-transition:enter="transition ease-out duration-150"
                                    x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                    class="absolute z-50 mt-2 w-full bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-2xl overflow-hidden min-w-[320px]">
                                    <div
                                        class="p-3 border-b border-slate-100 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-900/50">
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                                </svg>
                                            </div>
                                            <input x-ref="picSearchInput" x-model="search"
                                                @keydown.escape="open = false" placeholder="Cari nama vendor..."
                                                class="w-full pl-9 border-slate-200 dark:border-slate-700 dark:bg-slate-800 rounded-xl text-xs focus:ring-slate-900 focus:border-slate-900 py-2">
                                        </div>
                                    </div>
                                    <ul class="max-h-60 overflow-y-auto py-1">
                                        <template x-for="pic in filteredPics(search)" :key="pic.id">
                                            <li @click="selectedPicId = pic.id; selectedPicName = pic.name; open = false; search = ''"
                                                class="px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 cursor-pointer flex items-center justify-between group transition-colors">
                                                <span
                                                    class="text-sm font-bold text-slate-700 dark:text-slate-200 group-hover:text-slate-950 dark:group-hover:text-white transition-colors"
                                                    x-text="pic.name"></span>
                                                <div x-show="selectedPicId == pic.id"
                                                    class="w-2 h-2 rounded-full bg-emerald-500 shadow-sm shadow-emerald-500/50">
                                                </div>
                                            </li>
                                        </template>
                                        <div x-show="filteredPics(search).length === 0" class="px-4 py-8 text-center">
                                            <p class="text-sm text-slate-500 mb-4">Vendor "<span
                                                    x-text="search"></span>" tidak ditemukan.</p>
                                            <button type="button" @click="quickAddPic(search)" :disabled="isAdding"
                                                class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-lg transition-all active:scale-95 disabled:opacity-50">
                                                <svg x-show="!isAdding" class="w-3 h-3 mr-1.5" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                <svg x-show="isAdding"
                                                    class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" fill="none"
                                                    viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                <span
                                                    x-text="isAdding ? 'Menambah...' : ' Tambah & Pilih Vendor Baru'"></span>
                                            </button>
                                        </div>
                                    </ul>
                                </div>
                            </div>
                            @error('pic_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Invoice Number -->
                        <div>
                            <label for="invoice_number"
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                No. Invoice
                                <span class="font-normal normal-case text-slate-400">(opsional)</span>
                            </label>
                            <input id="invoice_number" type="text" name="invoice_number"
                                value="{{ old('invoice_number') }}"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm"
                                placeholder="Contoh: INV/2025/001">
                            @error('invoice_number')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date"
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Tanggal Jatuh Tempo
                                <span class="font-normal normal-case text-slate-400">(opsional)</span>
                            </label>
                            <input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm">
                            @error('due_date')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Amount -->
                        <div class="sm:col-span-2">
                            <label for="amount"
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Nominal (Rp) <span class="text-red-500">*</span>
                            </label>
                            <input id="amount" type="number" name="amount" value="{{ old('amount') }}" min="1"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm"
                                placeholder="Contoh: 5000000" required>
                            @error('amount')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>

                        <!-- Description -->
                        <div class="sm:col-span-2">
                            <label for="description"
                                class="block text-xs font-bold text-slate-500 dark:text-slate-400 mb-1.5 uppercase tracking-wider">
                                Keterangan <span class="text-red-500">*</span>
                            </label>
                            <textarea id="description" name="description" rows="3"
                                class="w-full rounded-xl border-slate-200 dark:border-slate-700 dark:bg-slate-900 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm py-2.5 shadow-sm"
                                placeholder="Contoh: Tagihan sewa gedung bulan Mei 2025"
                                required>{{ old('description') }}</textarea>
                            @error('description')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Info Note -->
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 flex items-start gap-3">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-xs text-blue-700 dark:text-blue-300">Tagihan yang dicatat di sini akan berstatus
                            <strong>Pending</strong> dan perlu diverifikasi oleh Akunting terlebih dahulu.
                        </p>
                    </div>

                    <div
                        class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                        <a href="{{ route('payables.index') }}"
                            class="px-5 py-2.5 text-sm font-medium text-slate-600 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 rounded-xl transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-sm shadow-primary-500/30 active:scale-95">
                            Simpan Tagihan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <script>
        function payableForm() {
            return {
                pics: @json($pics),
                selectedPicId: '{{ old('pic_id') }}',
                selectedPicName: '',
                isAdding: false,

                init() {
                    if (this.selectedPicId) {
                        const pic = this.pics.find(p => p.id == this.selectedPicId);
                        if (pic) this.selectedPicName = pic.name;
                    }
                },

                filteredPics(search) {
                    if (!search) return this.pics;
                    const s = search.toLowerCase();
                    return this.pics.filter(p => p.name.toLowerCase().includes(s));
                },

                async quickAddPic(name) {
                    if (!name) return;
                    this.isAdding = true;
                    try {
                        const response = await fetch('{{ route('pics.quick-store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ name: name })
                        });
                        const result = await response.json();
                        if (result.success) {
                            this.pics.push(result.data);
                            this.selectedPicId = result.data.id;
                            this.selectedPicName = result.data.name;
                        }
                    } catch (error) {
                        console.error('Error adding PIC:', error);
                        alert('Gagal menambah PIC baru.');
                    } finally {
                        this.isAdding = false;
                    }
                }
            }
        }
    </script>
</x-app-layout>