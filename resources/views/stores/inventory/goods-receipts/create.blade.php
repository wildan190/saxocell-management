@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('stores.index') }}" class="hover:text-slate-700">Stores</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('stores.show', $store) }}" class="hover:text-slate-700">{{ $store->name }}</a>
                        </li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('stores.goods-receipts.index', $store) }}" class="hover:text-slate-700">Goods
                                Receipts</a></li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Record Store Goods Receipt</h1>
                <p class="mt-2 text-sm text-slate-700">Fill in the details for the incoming goods at Store <span
                        class="font-semibold text-indigo-600">{{ $store->name }}</span>.</p>
            </div>
        </div>

        <form action="{{ route('stores.goods-receipts.store', $store) }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-8">
                {{-- 1. Items Received (Top) --}}
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight">1. Items Received</h3>
                        <button type="button" onclick="addItemRow()"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add New Item Row
                        </button>
                    </div>

                    <!-- SKU Datalist for Auto-fill -->
                    <datalist id="product-skus">
                        @foreach($products as $product)
                            <option value="{{ $product->sku }}">{{ $product->name }}</option>
                        @endforeach
                    </datalist>

                    <datalist id="category-list">
                        <option value="Laptop">
                        <option value="Smartphone">
                        <option value="Tablet">
                        <option value="Accessories">
                    </datalist>

                    <div id="items-container" class="space-y-6">
                        <!-- Smart Rows -->
                        <div class="item-row bg-slate-50/50 p-6 rounded-2xl border border-slate-200">
                            <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">
                                <!-- Product Info Section -->
                                <div class="lg:col-span-6 grid grid-cols-1 gap-4 sm:grid-cols-12">
                                    <div class="sm:col-span-3">
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">SKU
                                            / Code</label>
                                        <div class="relative group/sku">
                                            <input type="text" name="items[0][sku]" required list="product-skus"
                                                oninput="handleSkuInput(this)" placeholder="SKU"
                                                class="sku-input block w-full rounded-xl border-slate-200 pl-4 pr-10 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all bg-white">
                                            <button type="button" onclick="generateSku(this)" title="Generate SKU"
                                                class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-300 hover:text-indigo-600 transition-colors">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-5">
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product
                                            Name</label>
                                        <input type="text" name="items[0][name]" required placeholder="Name"
                                            class="name-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all bg-white">
                                    </div>
                                    <div class="sm:col-span-4">
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Category</label>
                                        <input type="text" name="items[0][category]" list="category-list"
                                            placeholder="Category"
                                            class="category-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all bg-white">
                                    </div>
                                </div>

                                <!-- Financial Info Section -->
                                <div class="lg:col-span-5 grid grid-cols-2 gap-4 lg:grid-cols-4 items-end">
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Purchase
                                            Price</label>
                                        <input type="number" name="items[0][purchase_price]" required step="0.01" min="0"
                                            oninput="calculateTotal()" placeholder="0"
                                            class="price-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-right transition-all bg-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                        <input type="number" name="items[0][quantity]" required step="1" min="1"
                                            oninput="calculateTotal()" placeholder="1"
                                            class="qty-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all bg-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Unit</label>
                                        <input type="text" name="items[0][unit]" required placeholder="Unit"
                                            class="unit-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all bg-white">
                                    </div>
                                    <div>
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Subtotal</label>
                                        <div
                                            class="subtotal-display block w-full px-2 py-3 text-sm font-black text-indigo-600 text-right">
                                            Rp 0</div>
                                    </div>
                                </div>

                                <!-- Action Section -->
                                <div class="lg:col-span-1 flex justify-center pt-6 lg:pt-8">
                                    <button type="button" onclick="this.closest('.item-row').remove(); calculateTotal();"
                                        class="p-2 text-slate-300 hover:text-red-500 transition-colors bg-white rounded-lg border border-slate-100 hover:shadow-sm">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100 flex items-center justify-between mt-6">
                        <div>
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest leading-none mb-1">
                                Total Purchase</p>
                            <h3 class="text-2xl font-black text-indigo-700" id="grand-total-display">Rp 0</h3>
                        </div>
                    </div>
                </div>

                <hr class="border-slate-100">

                {{-- 2. Receipt Info & Payment (Bottom) --}}
                <div class="space-y-8">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Received Date</label>
                            <input type="date" name="received_date" required value="{{ date('Y-m-d') }}"
                                class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Sender Name / Vendor</label>
                            <input type="text" name="sender_name" required placeholder="e.g. PT. Global Supply"
                                class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Notes / External Description</label>
                        <textarea name="notes" rows="2" placeholder="Any additional delivery info..."
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"></textarea>
                    </div>

                    <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900">Payment Accounts</h3>
                                <p class="text-xs text-slate-500 font-medium">Select source of funds from this store's
                                    accounts.</p>
                            </div>
                            <button type="button" onclick="addAccountRow()"
                                class="inline-flex items-center px-4 py-2 text-sm font-semibold text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors shadow-sm">
                                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Account
                            </button>
                        </div>
                        <div id="accounts-container" class="space-y-4">
                            <div
                                class="account-row grid grid-cols-1 sm:grid-cols-12 gap-4 items-end bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                                <div class="sm:col-span-7">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select
                                        Account</label>
                                    <select name="payment_accounts[0][id]" required
                                        class="block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-indigo-600 focus:border-indigo-600 transition-all bg-white font-black">
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }} — Rp
                                                {{ number_format($acc->balance, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-1">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 text-center">Fee</label>
                                    <div class="flex flex-col items-center gap-1.5 pt-2">
                                        <input type="checkbox" name="payment_accounts[0][has_fee]" value="1"
                                            onchange="calculateTotal()"
                                            class="fee-checkbox h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 transition-all">
                                        <span
                                            class="text-[10px] font-black text-slate-400 uppercase leading-none text-center">Admin
                                            Fee<br>Rp. 2500</span>
                                    </div>
                                </div>
                                <div class="sm:col-span-3">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Amount
                                        (Rp)</label>
                                    <input type="number" name="payment_accounts[0][amount]" required min="0"
                                        oninput="validatePayments()" placeholder="0"
                                        class="payment-amount block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-black text-right focus:ring-indigo-600 focus:border-indigo-600 transition-all">
                                </div>
                                <div class="sm:col-span-1 flex justify-center pb-1">
                                    <button type="button"
                                        onclick="this.closest('.account-row').remove(); validatePayments();"
                                        class="text-slate-200 hover:text-red-500 transition-colors">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-8 border-t border-slate-100">
                    <a href="{{ route('stores.goods-receipts.index', $store) }}"
                        class="px-6 py-2 text-sm font-black text-slate-400 uppercase tracking-widest hover:text-slate-600">Cancel</a>
                    <button type="submit"
                        class="rounded-2xl bg-slate-900 px-10 py-4 text-sm font-black text-white shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all active:scale-[0.98] uppercase tracking-widest">
                        Save Goods Receipt
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            const productMaster = {
                @foreach($products as $product)
                                            '{{ $product->sku }}': {
                        name: '{{ addslashes($product->name) }}',
                        unit: '{{ addslashes($product->unit) }}',
                        category: '{{ addslashes($product->category) }}'
                    },
                @endforeach
                            };

            let rowCount = 1;
            let accountCount = 1;

            window.handleSkuInput = function (input) {
                const row = input.closest('.item-row');
                const nameInput = row.querySelector('.name-input');
                const unitInput = row.querySelector('.unit-input');
                const categoryInput = row.querySelector('.category-input');
                const sku = input.value;

                if (productMaster[sku]) {
                    nameInput.value = productMaster[sku].name;
                    unitInput.value = productMaster[sku].unit;
                    categoryInput.value = productMaster[sku].category || '';
                }
            }

            window.calculateTotal = function () {
                let adminFees = 0;
                document.querySelectorAll('.fee-checkbox').forEach(cb => {
                    if (cb.checked) adminFees += 2500;
                });

                let grandTotal = adminFees;
                document.querySelectorAll('.item-row').forEach(row => {
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    const subtotal = price * qty;
                    row.querySelector('.subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                    grandTotal += subtotal;
                });
                document.getElementById('grand-total-display').textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');
                validatePayments();
            }

            window.validatePayments = function () {
                let totalPaid = 0;
                let adminFees = 0;
                document.querySelectorAll('.payment-amount').forEach((input, index) => {
                    const row = input.closest('.account-row');
                    const amount = parseFloat(input.value) || 0;
                    totalPaid += amount;

                    const cb = row.querySelector('.fee-checkbox');
                    if (cb && cb.checked) adminFees += 2500;
                });

                const itemRows = document.querySelectorAll('.item-row');
                if (itemRows.length === 1) {
                    const firstRow = itemRows[0];
                    const priceInput = firstRow.querySelector('.price-input');
                    const qtyInput = firstRow.querySelector('.qty-input');
                    const quantity = parseFloat(qtyInput.value) || 1;
                    const targetPrice = totalPaid / quantity;
                    if (targetPrice >= 0 && !isNaN(targetPrice)) {
                        priceInput.value = targetPrice.toFixed(2);
                        const subtotal = targetPrice * quantity;
                        firstRow.querySelector('.subtotal-display').textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                    }
                }

                let totalItems = 0;
                itemRows.forEach(row => {
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    totalItems += price * qty;
                });

                const grandTotal = totalItems + adminFees;
                const display = document.getElementById('grand-total-display');
                display.textContent = 'Rp ' + grandTotal.toLocaleString('id-ID');

                if (Math.abs(totalItems - totalPaid) > 0.01 && totalPaid > 0) {
                    display.classList.remove('text-indigo-700');
                    display.classList.add('text-red-600');
                } else {
                    display.classList.remove('text-red-600');
                    display.classList.add('text-indigo-700');
                }
            }

            window.addAccountRow = function () {
                const container = document.getElementById('accounts-container');
                const newRow = document.createElement('div');
                newRow.className = 'account-row grid grid-cols-1 sm:grid-cols-12 gap-4 items-end bg-slate-50/30 p-4 rounded-xl border border-slate-100/50';
                newRow.innerHTML = `
                                    <div class="sm:col-span-7">
                                        <select name="payment_accounts[${accountCount}][id]" required
                                            class="block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-indigo-600 focus:border-indigo-600 transition-all bg-white font-black">
                                            @foreach($accounts as $acc)
                                                <option value="{{ $acc->id }}">{{ $acc->name }} — Rp {{ number_format($acc->balance, 0, ',', '.') }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <div class="flex flex-col items-center gap-1.5 pt-2">
                                            <input type="checkbox" name="payment_accounts[${accountCount}][has_fee]" value="1"
                                                onchange="calculateTotal()"
                                                class="fee-checkbox h-5 w-5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 transition-all">
                                            <span class="text-[10px] font-black text-slate-400 uppercase leading-none text-center">Admin Fee<br>Rp. 2500</span>
                                        </div>
                                    </div>
                                    <div class="sm:col-span-3">
                                        <input type="number" name="payment_accounts[${accountCount}][amount]" required min="0" oninput="validatePayments()" placeholder="0"
                                            class="payment-amount block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-black text-right focus:ring-indigo-600 focus:border-indigo-600 transition-all">
                                    </div>
                                    <div class="sm:col-span-1 flex justify-center pb-1">
                                        <button type="button" onclick="this.closest('.account-row').remove(); validatePayments();" class="text-slate-300 hover:text-red-500 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                `;
                container.appendChild(newRow);
                accountCount++;
            }

            window.addItemRow = function () {
                const container = document.getElementById('items-container');
                const newRow = document.createElement('div');
                newRow.className = 'item-row bg-slate-50/50 p-6 rounded-2xl border border-slate-200';
                newRow.innerHTML = `
                                    <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 items-start">
                                        <div class="lg:col-span-6 grid grid-cols-1 gap-4 sm:grid-cols-12">
                                            <div class="sm:col-span-3">
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">SKU / Code</label>
                                                <div class="relative group/sku">
                                                    <input type="text" name="items[${rowCount}][sku]" required list="product-skus" oninput="handleSkuInput(this)"
                                                        class="sku-input block w-full rounded-xl border-slate-200 pl-4 pr-10 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all bg-white">
                                                    <button type="button" onclick="generateSku(this)" class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-300 hover:text-indigo-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="sm:col-span-5">
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product Name</label>
                                                <input type="text" name="items[${rowCount}][name]" required class="name-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all bg-white">
                                            </div>
                                            <div class="sm:col-span-4">
                                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Category</label>
                                                <input type="text" name="items[${rowCount}][category]" list="category-list" class="category-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all bg-white">
                                            </div>
                                        </div>
                                        <div class="lg:col-span-5 grid grid-cols-2 gap-4 lg:grid-cols-4 items-end">
                                            <div><label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Price</label><input type="number" name="items[${rowCount}][purchase_price]" required step="0.01" min="0" oninput="calculateTotal()" class="price-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-right transition-all bg-white"></div>
                                            <div><label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Qty</label><input type="number" name="items[${rowCount}][quantity]" required step="1" min="1" oninput="calculateTotal()" class="qty-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all bg-white"></div>
                                            <div><label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Unit</label><input type="text" name="items[${rowCount}][unit]" required class="unit-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all bg-white"></div>
                                            <div><label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Subtotal</label><div class="subtotal-display block w-full px-2 py-3 text-sm font-black text-indigo-600 text-right">Rp 0</div></div>
                                        </div>
                                        <div class="lg:col-span-1 flex justify-center pt-6 lg:pt-8">
                                            <button type="button" onclick="this.closest('.item-row').remove(); calculateTotal();" class="p-2 text-slate-300 hover:text-red-500 transition-colors bg-white rounded-lg border border-slate-100 hover:shadow-sm">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                `;
                container.appendChild(newRow);
                rowCount++;
            }

            window.generateSku = function (button) {
                const input = button.closest('.group\\/sku').querySelector('input');
                const random = Math.random().toString(36).substring(2, 8).toUpperCase();
                input.value = 'SX-' + random;
                handleSkuInput(input);
            }
        </script>
    @endpush
@endsection