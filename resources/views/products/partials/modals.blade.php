<!-- Price Adjustment Modal -->
<div id="price-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-12 animate-in fade-in zoom-in duration-500">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 leading-tight">Price Control</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Market Position Update
                </p>
            </div>
            <button type="button" onclick="closePriceModal()"
                class="group p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-8">
            @csrf
            @method('PUT')

            <input type="hidden" name="name" value="{{ $product->name }}">
            <input type="hidden" name="unit" value="{{ $product->unit }}">
            <input type="hidden" name="description" value="{{ $product->description }}">

            <div class="space-y-6">
                <div class="group">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 group-focus-within:text-indigo-600 transition-colors">Acquisition
                        Price (Buy)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-slate-300 font-black text-sm">Rp</span>
                        </div>
                        <input type="number" name="purchase_price" value="{{ (int) $product->purchase_price }}" required
                            class="block w-full pl-12 pr-6 py-4.5 rounded-[1.5rem] border-slate-100 bg-slate-50 font-black text-slate-900 focus:ring-[12px] focus:ring-indigo-500/5 focus:border-indigo-500 focus:bg-white transition-all text-lg">
                    </div>
                </div>

                <div class="group">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 group-focus-within:text-indigo-600 transition-colors">Display
                        Price (Sell)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-indigo-300 font-black text-sm">Rp</span>
                        </div>
                        <input type="number" name="selling_price" value="{{ (int) $product->selling_price }}" required
                            class="block w-full pl-12 pr-6 py-4.5 rounded-[1.5rem] border-slate-100 bg-slate-50 font-black text-indigo-600 focus:ring-[12px] focus:ring-indigo-500/5 focus:border-indigo-500 focus:bg-white transition-all text-lg tracking-tight">
                    </div>
                </div>

                <div class="group">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 group-focus-within:text-indigo-600 transition-colors">Context
                        for Change</label>
                    <textarea name="adjustment_reason" rows="2" placeholder="e.g. Supplier cost increase..."
                        class="block w-full px-6 py-4.5 rounded-[1.5rem] border-slate-100 bg-slate-50 text-sm font-bold text-slate-600 focus:ring-[12px] focus:ring-indigo-500/5 focus:border-indigo-500 focus:bg-white transition-all resize-none"></textarea>
                </div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closePriceModal()"
                    class="flex-1 px-8 py-5 rounded-[1.75rem] bg-slate-100 text-xs font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest">
                    Discard
                </button>
                <button type="submit"
                    class="flex-[1.5] px-8 py-5 rounded-[1.75rem] bg-slate-900 text-xs font-black text-white shadow-2xl shadow-slate-200 hover:bg-indigo-600 transition-all active:scale-[0.98] uppercase tracking-widest">
                    Authorize Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Record Activity Modal -->
<div id="activity-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg p-12 animate-in fade-in zoom-in duration-500">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 leading-tight">Record Event</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Operational Lifecycle
                    Log</p>
            </div>
            <button type="button" onclick="closeActivityModal()"
                class="group p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('products.activities.store', $product) }}" method="POST" class="space-y-8"
            x-data="{ cost: 0, type: 'repair' }">
            @csrf

            <div class="grid grid-cols-2 gap-6">
                <div class="group">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 group-focus-within:text-indigo-600 transition-colors">Event
                        Type</label>
                    <select name="activity_type" required x-model="type"
                        class="block w-full px-6 py-4.5 rounded-[1.5rem] border-slate-100 bg-slate-50 font-black text-slate-900 focus:ring-[12px] focus:ring-indigo-500/5 focus:border-indigo-500 focus:bg-white transition-all text-sm appearance-none cursor-pointer">
                        <option value="repair">Maintenance (Repair)</option>
                        <option value="improvement">Enhancement (Upgrade)</option>
                        <option value="status_change">State Transition</option>
                        <option value="other">General Log</option>
                    </select>
                </div>

                <div class="group">
                    <label
                        class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 group-focus-within:text-rose-600 transition-colors">Incurred
                        Cost</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                            <span class="text-slate-300 font-black text-xs uppercase">Rp</span>
                        </div>
                        <input type="number" name="cost" x-model="cost" placeholder="0"
                            class="block w-full pl-12 pr-6 py-4.5 rounded-[1.5rem] border-slate-100 bg-slate-50 font-black text-slate-900 focus:ring-[12px] focus:ring-rose-500/5 focus:border-rose-500 focus:bg-white transition-all text-lg">
                    </div>
                </div>
            </div>

            <div x-show="cost > 0" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4"
                class="p-6 bg-rose-50/50 rounded-[2rem] border border-rose-100 border-dashed">
                <label class="block text-[10px] font-black text-rose-500 uppercase tracking-widest mb-3 px-1">Authorize
                    Disbursement From</label>
                <select name="finance_account_id" :required="cost > 0"
                    class="block w-full px-6 py-4.5 rounded-[1.5rem] border-rose-100 bg-white shadow-sm font-black text-slate-900 focus:ring-[12px] focus:ring-rose-500/5 focus:border-rose-500 transition-all text-sm cursor-pointer">
                    <option value="">-- Select Source Account --</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }} ({{ number_format($acc->balance, 0, ',', '.') }})
                        </option>
                    @endforeach
                </select>
                <p class="mt-3 text-[9px] text-rose-400 px-1 font-bold italic uppercase tracking-tighter">This action
                    will create an immediate financial debit node.</p>
            </div>

            <div class="group">
                <label
                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 px-1 group-focus-within:text-indigo-600 transition-colors">Observations
                    & Notes</label>
                <textarea name="description" rows="3" required placeholder="Provide clinical details of the event..."
                    class="block w-full px-6 py-4.5 rounded-[1.5rem] border-slate-100 bg-slate-50 text-sm font-bold text-slate-600 focus:ring-[12px] focus:ring-indigo-500/5 focus:border-indigo-500 focus:bg-white transition-all resize-none leading-relaxed"></textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="button" onclick="closeActivityModal()"
                    class="flex-1 px-8 py-5 rounded-[1.75rem] bg-slate-100 text-xs font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest">
                    Cancel
                </button>
                <button type="submit"
                    class="flex-1 px-8 py-5 rounded-[1.75rem] bg-indigo-600 text-xs font-black text-white shadow-2xl shadow-indigo-100 hover:bg-slate-900 transition-all active:scale-[0.98] uppercase tracking-widest">
                    Commit Entry
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Quality Label Modal -->
<div id="label-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-lg p-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 leading-tight">Ubah Label</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Quality & Store
                    Classification</p>
            </div>
            <button type="button" onclick="closeLabelModal()"
                class="group p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('products.label.update', $product) }}" method="POST" class="space-y-8">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Label Kualitas
                    (Warehouse)</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="quality_label" value="none" class="sr-only peer" {{ $product->quality_label === 'none' ? 'checked' : '' }}>
                        <div
                            class="px-3 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-sm font-black text-slate-400 peer-checked:border-emerald-400 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 transition-all">
                            Sesuai</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="quality_label" value="yellow" class="sr-only peer" {{ $product->quality_label === 'yellow' ? 'checked' : '' }}>
                        <div
                            class="px-3 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-sm font-black text-slate-400 peer-checked:border-yellow-400 peer-checked:bg-yellow-50 peer-checked:text-yellow-700 transition-all">
                            Kurang Sesuai</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="quality_label" value="red" class="sr-only peer" {{ $product->quality_label === 'red' ? 'checked' : '' }}>
                        <div
                            class="px-3 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-sm font-black text-slate-400 peer-checked:border-rose-400 peer-checked:bg-rose-50 peer-checked:text-rose-700 transition-all">
                            Tidak Sesuai</div>
                    </label>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Keterangan
                    Label</label>
                <textarea name="quality_description" rows="3" placeholder="Deskripsikan kondisi produk..."
                    class="block w-full px-6 py-4 rounded-[1.5rem] border-slate-100 bg-slate-50 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all resize-none">{{ $product->quality_description }}</textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Label Toko
                    (Store)</label>
                <div class="grid grid-cols-3 gap-3">
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="store_label" value="none" class="sr-only peer" {{ $product->store_label === 'none' ? 'checked' : '' }}>
                        <div
                            class="px-3 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-xs font-black text-slate-400 peer-checked:border-slate-400 peer-checked:bg-slate-900 peer-checked:text-white transition-all">
                            Normal</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="store_label" value="grey" class="sr-only peer" {{ $product->store_label === 'grey' ? 'checked' : '' }}>
                        <div
                            class="px-3 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-xs font-black text-slate-400 peer-checked:border-slate-400 peer-checked:bg-slate-900 peer-checked:text-white transition-all">
                            Sembunyikan</div>
                    </label>
                    <label class="cursor-pointer text-center">
                        <input type="radio" name="store_label" value="red" class="sr-only peer" {{ $product->store_label === 'red' ? 'checked' : '' }}>
                        <div
                            class="px-3 py-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-xs font-black text-slate-400 peer-checked:border-rose-400 peer-checked:bg-rose-600 peer-checked:text-white transition-all">
                            Tidak Sesuai</div>
                    </label>
                </div>
            </div>
            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeLabelModal()"
                    class="flex-1 px-8 py-5 rounded-[1.75rem] bg-slate-100 text-xs font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest">Batal</button>
                <button type="submit"
                    class="flex-1 px-8 py-5 rounded-[1.75rem] bg-slate-900 text-xs font-black text-white hover:bg-indigo-600 transition-all active:scale-[0.98] uppercase tracking-widest">Simpan
                    Label</button>
            </div>
        </form>
    </div>
</div>

<!-- Return / Retur Modal -->
<div id="return-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 leading-tight">Ajukan Retur</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Send Back to Supplier
                </p>
            </div>
            <button type="button" onclick="closeReturnModal()"
                class="group p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('products.returns.ship', $product) }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">No. Resi
                    (opsional)</label>
                <input type="text" name="resi" placeholder="JNE123456789"
                    class="block w-full px-6 py-4 rounded-[1.5rem] border-slate-100 bg-slate-50 font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Biaya Ongkir
                    (jika ada)</label>
                <div class="relative">
                    <span
                        class="absolute inset-y-0 left-0 pl-5 flex items-center text-slate-300 font-black text-sm">Rp</span>
                    <input type="number" name="shipping_cost" placeholder="0"
                        class="block w-full pl-12 pr-6 py-4 rounded-[1.5rem] border-slate-100 bg-slate-50 font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                </div>
            </div>
            <div>
                <label
                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan</label>
                <textarea name="notes" rows="2" placeholder="e.g. Layar retak, dikembalikan ke supplier..."
                    class="block w-full px-6 py-4 rounded-[1.5rem] border-slate-100 bg-slate-50 text-sm font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all resize-none"></textarea>
            </div>
            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeReturnModal()"
                    class="flex-1 px-8 py-4 rounded-[1.75rem] bg-slate-100 text-xs font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest">Batal</button>
                <button type="submit"
                    class="flex-1 px-8 py-4 rounded-[1.75rem] bg-rose-600 text-xs font-black text-white hover:bg-rose-700 transition-all active:scale-[0.98] uppercase tracking-widest">Kirimkan
                    Barang</button>
            </div>
        </form>
    </div>
</div>

<!-- Compensation Modal (Yellow only) -->
<div id="compensation-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 leading-tight">Kompensasi Harga</h2>
                <p class="text-[10px] font-black text-yellow-500 uppercase tracking-widest mt-1">Price Adjustment for
                    Defect</p>
            </div>
            <button type="button" onclick="closeCompensationModal()"
                class="group p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form action="{{ route('products.label.update', $product) }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="quality_label" value="{{ $product->quality_label }}">
            <input type="hidden" name="store_label" value="{{ $product->store_label }}">
            <div class="p-5 bg-yellow-50 rounded-2xl border border-yellow-100">
                <p class="text-[10px] font-black text-yellow-600 uppercase tracking-widest mb-1">Harga Jual Saat Ini</p>
                <p class="text-3xl font-black text-yellow-700">Rp
                    {{ number_format($product->selling_price, 0, ',', '.') }}</p>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Harga
                    Kompensasi (baru)</label>
                <div class="relative">
                    <span
                        class="absolute inset-y-0 left-0 pl-5 flex items-center text-yellow-400 font-black text-sm">Rp</span>
                    <input type="number" name="compensation_price" required placeholder="0"
                        class="block w-full pl-12 pr-6 py-4 rounded-[1.5rem] border-yellow-100 bg-yellow-50 font-black text-yellow-700 focus:ring-4 focus:ring-yellow-500/10 focus:border-yellow-500 transition-all text-xl">
                </div>
            </div>
            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closeCompensationModal()"
                    class="flex-1 px-8 py-4 rounded-[1.75rem] bg-slate-100 text-xs font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest">Batal</button>
                <button type="submit"
                    class="flex-1 px-8 py-4 rounded-[1.75rem] bg-yellow-500 text-xs font-black text-white hover:bg-yellow-600 transition-all active:scale-[0.98] uppercase tracking-widest">Simpan
                    Kompensasi</button>
            </div>
        </form>
    </div>
</div>

<!-- Return Payment Modal -->
<div id="payment-modal"
    class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md">
    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-12">
        <div class="flex items-center justify-between mb-10">
            <div>
                <h2 class="text-3xl font-black text-slate-900 leading-tight">Nominal Kembali</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Stackable Refund Entry
                </p>
            </div>
            <button type="button" onclick="closePaymentModal()"
                class="group p-2 rounded-xl bg-slate-50 text-slate-400 hover:text-rose-500 transition-all">
                <svg class="w-6 h-6 transform group-hover:rotate-90 transition-transform duration-300" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <form id="payment-form" action="" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Nominal yang
                    Dikembalikan</label>
                <div class="relative">
                    <span
                        class="absolute inset-y-0 left-0 pl-5 flex items-center text-emerald-400 font-black text-sm">Rp</span>
                    <input type="number" name="amount" required placeholder="0"
                        class="block w-full pl-12 pr-6 py-4 rounded-[1.5rem] border-emerald-100 bg-emerald-50 font-black text-emerald-700 focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all text-xl">
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Akun
                    Tujuan</label>
                <select name="finance_account_id" required
                    class="block w-full px-6 py-4 rounded-[1.5rem] border-slate-100 bg-slate-50 font-black text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                    <option value="">-- Pilih Akun --</option>
                    @foreach($accounts as $acc)
                        <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label
                    class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Catatan</label>
                <input type="text" name="notes" placeholder="e.g. Transfer pertama dari supplier..."
                    class="block w-full px-6 py-4 rounded-[1.5rem] border-slate-100 bg-slate-50 font-bold text-slate-600 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
            </div>
            <div class="flex gap-4 pt-2">
                <button type="button" onclick="closePaymentModal()"
                    class="flex-1 px-8 py-4 rounded-[1.75rem] bg-slate-100 text-xs font-black text-slate-500 hover:bg-slate-200 transition-all uppercase tracking-widest">Batal</button>
                <button type="submit"
                    class="flex-1 px-8 py-4 rounded-[1.75rem] bg-emerald-600 text-xs font-black text-white hover:bg-emerald-700 transition-all active:scale-[0.98] uppercase tracking-widest">Tambah
                    Nominal</button>
            </div>
        </form>
    </div>
</div>