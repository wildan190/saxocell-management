@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-7 text-slate-900 sm:truncate tracking-tight">New Warehouse to Store
                    Transfer</h2>
                <p class="mt-2 text-sm text-slate-500">Authorized movement of stock from a central warehouse to a retail
                    store.</p>
            </div>
        </div>

        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-rose-50 p-4 border border-rose-100">
                <ul class="list-disc pl-5 text-sm text-rose-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('inventory.warehouse-to-store.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Sidebar: Transfer Details -->
                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 p-8 space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">From Warehouse</label>
                            <select name="from_warehouse_id" id="from_warehouse_id" required
                                class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all text-orange-600 font-bold">
                                <option value="" disabled selected>Select Warehouse</option>
                                @foreach ($warehouses as $warehouse)
                                    <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900">To Store</label>
                            <select name="to_store_id" required
                                class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all text-indigo-600 font-bold">
                                <option value="" disabled selected>Select Store</option>
                                @foreach ($stores as $store)
                                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Transfer Date</label>
                            <input type="date" name="transfer_date" required value="{{ date('Y-m-d') }}"
                                class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Notes (Optional)</label>
                            <textarea name="notes" rows="3"
                                class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                                placeholder="Special instructions..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Main Content: Items Selection -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-tight text-sm">Transfer Items
                            </h3>
                            <button type="button" onclick="addItemRow()"
                                class="inline-flex items-center text-xs font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                                <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                Add Item
                            </button>
                        </div>

                        <div id="items-container" class="divide-y divide-slate-50">
                            <!-- Transfer Row Template -->
                            <div class="item-row p-8 group relative bg-white transition-colors hover:bg-slate-50/30">
                                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                                    <div class="lg:col-span-8">
                                        <label
                                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select
                                            Product</label>
                                        <select name="items[0][product_id]" required onchange="handleProductSelect(this)"
                                            class="product-select block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm font-semibold transition-all">
                                            <option value="" disabled selected>Search Product...</option>
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="lg:col-span-4 flex items-center gap-3">
                                        <div class="flex-1">
                                            <label
                                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                            <input type="number" name="items[0][quantity]" required min="1" step="1"
                                                class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center font-bold transition-all">
                                        </div>
                                        <div class="pt-6">
                                            <span
                                                class="stock-info text-[10px] font-bold text-slate-400 italic block mt-1">Stock:
                                                -</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-8 flex items-center justify-end border-t border-slate-100">
                            <button type="submit"
                                class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-[0.98]">
                                Execute Transfer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        const warehouseStocks = @json($warehouseStock);
        let rowCount = 1;

        function handleProductSelect(select) {
            const warehouseId = document.getElementById('from_warehouse_id').value;
            const stockInfo = select.closest('.item-row').querySelector('.stock-info');

            if (!warehouseId) {
                alert('Please select a source warehouse first.');
                select.value = '';
                return;
            }

            const productId = select.value;
            const stock = warehouseStocks[warehouseId]?.find(i => i.product_id == productId)?.quantity || 0;

            stockInfo.textContent = `Stock: ${stock}`;

            const qtyInput = select.closest('.item-row').querySelector('input[type="number"]');
            qtyInput.max = stock;
        }

        function addItemRow() {
            const container = document.getElementById('items-container');
            const newRow = document.createElement('div');
            newRow.className = 'item-row p-8 group relative bg-white transition-colors hover:bg-slate-50/30 border-t border-slate-50';

            newRow.innerHTML = `
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-end">
                            <div class="lg:col-span-8">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Select Product</label>
                                <select name="items[${rowCount}][product_id]" required onchange="handleProductSelect(this)"
                                    class="product-select block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm font-semibold transition-all">
                                    <option value="" disabled selected>Search Product...</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="lg:col-span-4 flex items-center gap-3">
                                <div class="flex-1">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                    <input type="number" name="items[${rowCount}][quantity]" required min="1" step="1"
                                        class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center font-bold transition-all">
                                </div>
                                <div class="pt-6">
                                    <span class="stock-info text-[10px] font-bold text-slate-400 italic block mt-1">Stock: -</span>
                                </div>
                                <button type="button" onclick="this.closest('.item-row').remove()" class="text-rose-400 hover:text-rose-600 transition-colors mb-2">
                                     <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                </button>
                            </div>
                        </div>
                    `;

            container.appendChild(newRow);
            rowCount++;
        }

        document.getElementById('from_warehouse_id').addEventListener('change', function () {
            // Reset all stock info when warehouse changes
            document.querySelectorAll('.stock-info').forEach(span => span.textContent = 'Stock: -');
            document.querySelectorAll('.product-select').forEach(select => select.value = '');
        });
    </script>
@endsection