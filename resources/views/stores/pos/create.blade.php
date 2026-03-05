@extends('layouts.app')

@section('content')
    <div x-data="posTerminal()" class="pb-24">
        {{-- Header --}}
        <div class="flex items-center gap-3 mb-8">
            <a href="{{ route('stores.pos.index', $store) }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Terminal Kasir</h1>
                <p class="text-slate-500 font-medium">{{ $store->name }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="mb-8 rounded-2xl bg-red-50 border border-red-200 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">Terdapat kesalahan pada transaksi:</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

            {{-- ===== LEFT: Product Grid ===== --}}
            <div class="xl:col-span-2 space-y-4">
                {{-- Search --}}
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                    <input type="text" x-model="search" placeholder="Cari produk..."
                        class="w-full pl-12 pr-4 py-3.5 rounded-2xl border border-slate-200 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                </div>

                {{-- Product Grid --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 max-h-[calc(100vh-260px)] overflow-y-auto pr-1">
                    @foreach($products as $sp)
                        <button type="button"
                            x-show="search === '' || '{{ strtolower($sp->product->name . ' ' . $sp->product->sku) }}'.includes(search.toLowerCase())"
                            @click="addItem({{ $sp->id }}, '{{ addslashes($sp->product->name) }}', '{{ $sp->product->sku ?? '' }}', {{ $sp->price }}, {{ $sp->stock }})"
                            class="group relative text-left bg-white rounded-[24px] p-4 ring-1 ring-slate-100 hover:ring-indigo-400 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 active:scale-95">
                            <div
                                class="aspect-square rounded-xl overflow-hidden bg-slate-50 mb-3 flex items-center justify-center">
                                @if($sp->image_path)
                                    <img src="{{ asset('storage/' . $sp->image_path) }}" alt="{{ $sp->product->name }}"
                                        class="w-full h-full object-cover">
                                @else
                                    <svg class="w-10 h-10 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 8.25h3m-3 3h3m-3 3h3M6.75 21h10.5" />
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm font-black text-slate-900 leading-tight truncate">{{ $sp->product->name }}</p>
                            <div class="flex items-center justify-between mt-0.5">
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">Stok: {{ $sp->stock }}</p>
                                <p class="text-[9px] text-slate-400 font-bold uppercase tracking-tighter">Modal: Rp {{ number_format($sp->product->purchase_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="mt-1 flex items-center justify-between">
                                <p class="text-sm font-black text-indigo-600">Rp {{ number_format($sp->price, 0, ',', '.') }}</p>
                                <div class="flex flex-col items-end">
                                    <p class="text-[8px] font-black text-rose-500 leading-none">Min: Rp {{ number_format($sp->min_price, 0, ',', '.') }}</p>
                                    <p class="text-[8px] font-black text-emerald-500 leading-none mt-0.5">Max: Rp {{ number_format($sp->max_price, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <span
                                    class="flex items-center justify-center w-7 h-7 rounded-full bg-indigo-600 text-white shadow-md">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- ===== RIGHT: Cart & Payment ===== --}}
            <div class="xl:col-span-1">
                <form action="{{ route('stores.pos.store', $store) }}" method="POST" id="pos-form">
                    @csrf
                    <div class="bg-white rounded-[28px] ring-1 ring-slate-100 shadow-sm overflow-hidden sticky top-6">

                        {{-- Cart Header --}}
                        <div class="px-6 pt-6 pb-4 border-b border-slate-100">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-black text-slate-900">Keranjang</h2>
                                <span x-text="cart.length + ' item'"
                                    class="text-xs font-black text-slate-400 uppercase tracking-widest"></span>
                            </div>
                        </div>

                        {{-- Cart Items --}}
                        <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                            <template x-if="cart.length === 0">
                                <div class="py-12 text-center text-slate-300">
                                    <svg class="w-10 h-10 mx-auto mb-2" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                                    </svg>
                                    <p class="text-sm font-bold">Tambahkan produk</p>
                                </div>
                            </template>
                            <template x-for="(item, idx) in cart" :key="idx">
                                <div class="px-6 py-4 flex items-center gap-3">
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-black text-slate-900 truncate" x-text="item.name"></p>
                                        <p class="text-xs text-slate-400 font-medium"
                                            x-text="'Rp ' + formatNum(item.price)"></p>
                                        <input type="hidden" :name="'items[' + idx + '][store_product_id]'"
                                            :value="item.id">
                                        <input type="hidden" :name="'items[' + idx + '][quantity]'" :value="item.qty">
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        <button type="button" @click="decQty(idx)"
                                            class="w-7 h-7 rounded-full bg-slate-100 hover:bg-red-100 hover:text-red-600 flex items-center justify-center font-black text-slate-500 transition-colors">−</button>
                                        <span x-text="item.qty"
                                            class="w-6 text-center text-sm font-black text-slate-900"></span>
                                        <button type="button" @click="incQty(idx)"
                                            class="w-7 h-7 rounded-full bg-slate-100 hover:bg-green-100 hover:text-green-600 flex items-center justify-center font-black text-slate-500 transition-colors">+</button>
                                    </div>
                                    <p class="text-sm font-black text-indigo-700 w-20 text-right flex-shrink-0"
                                        x-text="'Rp ' + formatNum(item.price * item.qty)"></p>
                                </div>
                            </template>
                        </div>

                        {{-- Totals & Form --}}
                        <div class="px-6 py-5 border-t border-slate-100 space-y-4 bg-slate-50/50">
                            {{-- Subtotal --}}
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500 font-medium">Subtotal</span>
                                <span class="font-black text-slate-900" x-text="'Rp ' + formatNum(subtotal())"></span>
                            </div>
                            {{-- Discount --}}
                            <div class="flex items-center gap-3">
                                <label class="text-sm text-slate-500 font-medium w-24 flex-shrink-0">Diskon (Rp)</label>
                                <input type="number" name="discount" x-model.number="discount" min="0" placeholder="0"
                                    class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm font-bold text-right focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            </div>
                            {{-- Total --}}
                            <div class="flex justify-between text-base border-t border-slate-200 pt-3">
                                <span class="font-black text-slate-700">Total</span>
                                <span class="font-black text-indigo-700 text-lg" x-text="'Rp ' + formatNum(total())"></span>
                            </div>
                            {{-- Amount Paid --}}
                            <div class="flex items-center gap-3" x-show="!isSplit">
                                <label class="text-sm text-slate-500 font-medium w-24 flex-shrink-0">Dibayar (Rp)</label>
                                <input type="number" name="amount_paid" x-model.number="amountPaid" min="0" placeholder="0"
                                    class="flex-1 rounded-xl border border-slate-200 px-3 py-2 text-sm font-bold text-right focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                            </div>
                            {{-- Split Payment Inputs --}}
                            <div class="space-y-3 pt-2 border-t border-slate-200 mt-2" x-show="isSplit">
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Detail Split
                                    Payment</p>
                                <div class="space-y-4">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-3">
                                            <label class="text-xs text-slate-500 font-bold w-20 flex-shrink-0">Tunai</label>
                                            <input type="number" name="split_cash" x-model.number="splitCash" min="0"
                                                placeholder="0"
                                                class="flex-1 rounded-xl border border-slate-200 px-3 py-1.5 text-sm font-bold text-right focus:outline-none focus:ring-2 focus:ring-green-500 bg-white">
                                        </div>
                                        <select name="split_cash_account_id" :required="isSplit && splitCash > 0"
                                            class="w-full rounded-lg border border-slate-100 px-3 py-1.5 text-[10px] font-bold focus:outline-none focus:ring-2 focus:ring-green-500 bg-slate-50">
                                            <option value="">-- Pilih Akun Tunai --</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-3">
                                            <label
                                                class="text-xs text-slate-500 font-bold w-20 flex-shrink-0">Transfer</label>
                                            <input type="number" name="split_transfer" x-model.number="splitTransfer"
                                                min="0" placeholder="0"
                                                class="flex-1 rounded-xl border border-slate-200 px-3 py-1.5 text-sm font-bold text-right focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                                        </div>
                                        <select name="split_transfer_account_id" :required="isSplit && splitTransfer > 0"
                                            class="w-full rounded-lg border border-slate-100 px-3 py-1.5 text-[10px] font-bold focus:outline-none focus:ring-2 focus:ring-blue-500 bg-slate-50">
                                            <option value="">-- Pilih Akun Transfer --</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-3">
                                            <label class="text-xs text-slate-500 font-bold w-20 flex-shrink-0">QRIS</label>
                                            <input type="number" name="split_qris" x-model.number="splitQris" min="0"
                                                placeholder="0"
                                                class="flex-1 rounded-xl border border-slate-200 px-3 py-1.5 text-sm font-bold text-right focus:outline-none focus:ring-2 focus:ring-purple-500 bg-white">
                                        </div>
                                        <select name="split_qris_account_id"
                                            class="w-full rounded-lg border border-slate-100 px-3 py-1.5 text-[10px] font-bold focus:outline-none focus:ring-2 focus:ring-purple-500 bg-slate-50">
                                            <option value="">-- Pilih Akun QRIS (Opsional) --</option>
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-between items-center text-xs font-black pt-1 px-1">
                                    <span class="text-slate-400">Total Dibayar</span>
                                    <span :class="totalPaid() < total() ? 'text-red-500' : 'text-green-600'"
                                        x-text="'Rp ' + formatNum(totalPaid())"></span>
                                </div>
                            </div>
                            {{-- Change --}}
                            <div class="flex justify-between text-sm bg-green-50 rounded-xl px-4 py-2"
                                x-show="change() >= 0 && totalPaid() > 0">
                                <span class="text-green-700 font-bold">Kembalian</span>
                                <span class="font-black text-green-700" x-text="'Rp ' + formatNum(change())"></span>
                            </div>
                        </div>

                        {{-- Payment Method & Info --}}
                        <div class="px-6 pb-4 space-y-4">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-xs font-black text-slate-400 uppercase tracking-widest">Metode
                                        Bayar</label>
                                    <button type="button"
                                        @click="isSplit = !isSplit; paymentMethod = isSplit ? 'split' : 'cash'"
                                        class="text-[10px] font-black px-2 py-1 rounded-lg transition-all"
                                        :class="isSplit ? 'bg-indigo-600 text-white shadow-md' : 'bg-slate-100 text-slate-400 hover:bg-slate-200'">
                                        <span x-text="isSplit ? '✕ Matikan Split' : '+ Split Payment'"></span>
                                    </button>
                                </div>
                                <input type="hidden" name="payment_method" :value="paymentMethod">
                                <div class="grid grid-cols-3 gap-2" x-show="!isSplit">
                                    @foreach(['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'] as $val => $label)
                                        <label class="relative cursor-pointer">
                                            <input type="radio" x-model="paymentMethod" value="{{ $val }}" class="peer sr-only">
                                            <span
                                                class="block text-center rounded-xl py-2.5 text-xs font-black border border-slate-200 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 text-slate-500 hover:border-slate-300 transition-all">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <div x-show="isSplit"
                                    class="rounded-xl bg-indigo-50 border border-indigo-100 p-3 text-center">
                                    <p class="text-xs font-black text-indigo-700">Mode Split Payment Aktif</p>
                                    <p class="text-[10px] text-indigo-400 font-medium">Input jumlah bayar di bagian atas</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label
                                        class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 block">Kasir</label>
                                    <input type="text" name="cashier_name" value="{{ auth()->user()->name }}" required
                                        placeholder="Nama kasir"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label
                                        class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 block">Pelanggan</label>
                                    <input type="text" name="customer_name" placeholder="Opsional"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                </div>
                            </div>

                            <div x-show="!isSplit">
                                <label class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 block">Catat
                                    ke Akun Keuangan <span class="text-red-400">*</span></label>
                                @if($accounts->isEmpty())
                                    <div
                                        class="rounded-xl bg-amber-50 border border-amber-200 px-3 py-2.5 text-xs font-bold text-amber-700">
                                        ⚠ Belum ada akun keuangan untuk toko ini. <a
                                            href="{{ route('stores.finance.accounts.index', $store) }}" class="underline">Buat
                                            akun dahulu</a>.
                                    </div>
                                @else
                                    <select name="finance_account_id" :required="!isSplit"
                                        class="w-full rounded-xl border border-slate-200 px-3 py-2.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 bg-white">
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }} — Rp
                                                {{ number_format($acc->balance, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>

                            <button type="submit" :disabled="cart.length === 0"
                                class="w-full rounded-2xl py-4 text-sm font-black text-white shadow-xl transition-all active:scale-[0.98] disabled:opacity-40 disabled:cursor-not-allowed"
                                :class="cart.length > 0 ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-200' : 'bg-slate-300'">
                                <span x-show="cart.length === 0">Tambahkan produk dahulu</span>
                                <span x-show="cart.length > 0">Proses Transaksi →</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function posTerminal() {
            return {
                cart: [],
                discount: 0,
                amountPaid: 0,
                splitCash: 0,
                splitTransfer: 0,
                splitQris: 0,
                isSplit: false,
                paymentMethod: 'cash',
                search: '',

                addItem(id, name, sku, price, stock) {
                    const idx = this.cart.findIndex(i => i.id === id);
                    if (idx >= 0) {
                        if (this.cart[idx].qty < stock) this.cart[idx].qty++;
                    } else {
                        this.cart.push({ id, name, sku, price, qty: 1, stock });
                    }
                },

                incQty(idx) {
                    if (this.cart[idx].qty < this.cart[idx].stock) this.cart[idx].qty++;
                },

                decQty(idx) {
                    if (this.cart[idx].qty > 1) {
                        this.cart[idx].qty--;
                    } else {
                        this.cart.splice(idx, 1);
                    }
                },

                subtotal() {
                    return this.cart.reduce((s, i) => s + i.price * i.qty, 0);
                },

                total() {
                    return Math.max(0, this.subtotal() - (this.discount || 0));
                },

                totalPaid() {
                    if (this.isSplit) {
                        return (this.splitCash || 0) + (this.splitTransfer || 0) + (this.splitQris || 0);
                    }
                    return this.amountPaid || 0;
                },

                change() {
                    return Math.max(0, this.totalPaid() - this.total());
                },

                formatNum(n) {
                    return Math.round(n).toLocaleString('id-ID');
                }
            }
        }
    </script>
@endsection