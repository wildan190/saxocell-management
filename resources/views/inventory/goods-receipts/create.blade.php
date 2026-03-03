@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('warehouses.index') }}" class="hover:text-slate-700">Warehouses</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('inventory.goods-receipts.index', $warehouse) }}"
                                class="hover:text-slate-700">Goods Receipts</a></li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Record Goods Receipt</h1>
                <p class="mt-2 text-sm text-slate-700">Fill in the details for the incoming goods at <span
                        class="font-semibold text-indigo-600">{{ $warehouse->name }}</span>.</p>
            </div>
        </div>

        <form action="{{ route('inventory.goods-receipts.store', $warehouse) }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-6">
                <div class="border-b border-slate-100 pb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Payment Accounts (Deduct from)</h3>
                        <button type="button" onclick="addAccountRow()"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-emerald-600 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition-colors shadow-sm">
                            <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Account
                        </button>
                    </div>
                    <div id="accounts-container" class="space-y-3">
                        <div
                            class="account-row grid grid-cols-1 sm:grid-cols-12 gap-4 items-end bg-slate-50/30 p-4 rounded-xl border border-slate-100/50">
                            <div class="sm:col-span-7">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 text-center sm:text-left">Select
                                    Account</label>
                                <select name="payment_accounts[0][id]" required
                                    class="block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-indigo-600 focus:border-indigo-600 transition-all bg-white">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} — Rp
                                            {{ number_format($acc->balance, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-4">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 text-center sm:text-left">Amount
                                    (Rp)</label>
                                <input type="number" name="payment_accounts[0][amount]" required min="0"
                                    oninput="validatePayments()" placeholder="0"
                                    class="payment-amount block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-black text-right focus:ring-indigo-600 focus:border-indigo-600 transition-all">
                            </div>
                            <div class="sm:col-span-1 flex justify-center pb-1">
                                <button type="button" onclick="this.closest('.account-row').remove(); validatePayments();"
                                    class="text-slate-300 hover:text-red-500 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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

                <div class="border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Items Received</h3>
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

                    <div id="items-container" class="space-y-4">
                        <!-- Smart Rows -->
                        <div class="item-row bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 items-end">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">SKU
                                    / Code</label>
                                <div class="relative group/sku">
                                    <input type="text" name="items[0][sku]" required list="product-skus"
                                        oninput="handleSkuInput(this)" placeholder="SKU"
                                        class="sku-input block w-full rounded-xl border-slate-200 pl-4 pr-10 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                    <button type="button" onclick="generateSku(this)" title="Generate SKU"
                                        class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-300 hover:text-indigo-600 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="lg:col-span-3">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product
                                        Name</label>
                                    <input type="text" name="items[0][name]" required placeholder="Name"
                                        class="name-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                </div>
                                <div class="lg:col-span-2">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Purchase
                                        Price</label>
                                    <input type="number" name="items[0][purchase_price]" required step="0.01" min="0"
                                        oninput="calculateTotal()" placeholder="0"
                                        class="price-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-right transition-all">
                                </div>
                                <div class="lg:col-span-1">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                    <input type="number" name="items[0][quantity]" required step="1" min="1"
                                        oninput="calculateTotal()" placeholder="0"
                                        class="qty-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                                </div>
                                <div class="lg:col-span-1">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Subtotal</label>
                                    <div
                                        class="subtotal-display block w-full px-2 py-3 text-sm font-black text-indigo-600 text-right">
                                        0</div>
                                </div>
                                <div class="lg:col-span-1">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Unit</label>
                                    <input type="text" name="items[0][unit]" required placeholder="Unit"
                                        class="unit-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                                </div>
                                <div class="lg:col-span-1 flex justify-center pb-1">
                                    <button type="button" onclick="this.closest('.item-row').remove(); calculateTotal();"
                                        class="text-slate-300 hover:text-red-500 transition-colors">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-indigo-50/50 rounded-2xl p-6 border border-indigo-100 flex items-center justify-between mt-6">
                    <div>
                        <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest leading-none mb-1">Total
                            Purchase</p>
                        <h3 class="text-2xl font-black text-indigo-700" id="grand-total-display">Rp 0</h3>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('inventory.goods-receipts.index', $warehouse) }}"
                        class="px-6 py-2 text-sm font-semibold text-slate-700">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all shadow-indigo-200">
                        Save Goods Receipt
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Product Master Data for quick lookup
            const productMaster = {
                @foreach($products as $product)
                                                                                '{{ $product->sku }}': {
                        name: '{{ addslashes($product->name) }}',
                        unit: '{{ addslashes($product->unit) }}',
                        description: '{{ addslashes($product->description) }}'
                    },
                @endforeach
                                                    };

            let rowCount = 1;
            let accountCount = 1;

            window.handleSkuInput = function (input) {
                const row = input.closest('.item-row');
                const nameInput = row.querySelector('.name-input');
                const unitInput = row.querySelector('.unit-input');
                const sku = input.value;

                if (productMaster[sku]) {
                    nameInput.value = productMaster[sku].name;
                    unitInput.value = productMaster[sku].unit;
                }
            }

            window.calculateTotal = function () {
                let grandTotal = 0;
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
                let totalItems = 0;
                document.querySelectorAll('.item-row').forEach(row => {
                    const price = parseFloat(row.querySelector('.price-input').value) || 0;
                    const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                    totalItems += price * qty;
                });

                let totalPaid = 0;
                document.querySelectorAll('.payment-amount').forEach(input => {
                    totalPaid += parseFloat(input.value) || 0;
                });

                const display = document.getElementById('grand-total-display');
                if (Math.abs(totalItems - totalPaid) > 0.01 && totalPaid > 0) {
                    display.classList.remove('text-indigo-700');
                    display.classList.add('text-red-600');
                    display.title = "Total payment (Rp " + totalPaid.toLocaleString('id-ID') + ") does not match total purchase.";
                } else {
                    display.classList.remove('text-red-600');
                    display.classList.add('text-indigo-700');
                    display.title = "";
                }
            }

            window.addAccountRow = function () {
                const container = document.getElementById('accounts-container');
                const newRow = document.createElement('div');
                newRow.className = 'account-row grid grid-cols-1 sm:grid-cols-12 gap-4 items-end bg-slate-50/30 p-4 rounded-xl border border-slate-100/50';

                newRow.innerHTML = `
                            <div class="sm:col-span-7">
                                <select name="payment_accounts[${accountCount}][id]" required
                                    class="block w-full rounded-xl border-slate-200 px-4 py-2.5 text-sm font-bold focus:ring-indigo-600 focus:border-indigo-600 transition-all bg-white">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} — Rp {{ number_format($acc->balance, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-4">
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
                newRow.className = 'item-row bg-slate-50/50 p-5 rounded-2xl border border-slate-100';

                newRow.innerHTML = `
                                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 items-end">
                                                <div class="lg:col-span-2">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">SKU / Code</label>
                                                    <div class="relative group/sku">
                                                        <input type="text" name="items[${rowCount}][sku]" required list="product-skus" oninput="handleSkuInput(this)"
                                                            placeholder="SKU"
                                                            class="sku-input block w-full rounded-xl border-slate-200 pl-4 pr-10 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                                        <button type="button" onclick="generateSku(this)" title="Generate SKU"
                                                            class="absolute right-2 top-1/2 -translate-y-1/2 p-1.5 text-slate-300 hover:text-indigo-600 transition-colors">
                                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="lg:col-span-3">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product Name</label>
                                                    <input type="text" name="items[${rowCount}][name]" required placeholder="Name"
                                                        class="name-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                                </div>
                                                <div class="lg:col-span-2">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Purchase Price</label>
                                                    <input type="number" name="items[${rowCount}][purchase_price]" required step="0.01" min="0" oninput="calculateTotal()" placeholder="0"
                                                        class="price-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-right transition-all">
                                                </div>
                                                <div class="lg:col-span-1">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                                    <input type="number" name="items[${rowCount}][quantity]" required step="1" min="1" oninput="calculateTotal()" placeholder="0"
                                                        class="qty-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                                                </div>
                                                <div class="lg:col-span-1">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Subtotal</label>
                                                    <div class="subtotal-display block w-full px-2 py-3 text-sm font-black text-indigo-600 text-right">0</div>
                                                </div>
                                                <div class="lg:col-span-1">
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Unit</label>
                                                    <input type="text" name="items[${rowCount}][unit]" required placeholder="Unit"
                                                        class="unit-input block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                                                </div>
                                                <div class="lg:col-span-1 flex justify-center pb-1">
                                                    <button type="button" onclick="this.closest('.item-row').remove(); calculateTotal();" class="text-slate-300 hover:text-red-500 transition-colors">
                                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
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