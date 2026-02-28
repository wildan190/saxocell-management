@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('stores.transfers.index', $store) }}"
                class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">New Inter-Store Transfer</h1>
                <p class="text-sm text-slate-500 mt-1">Select destination store and items to transfer.</p>
            </div>
        </div>

        <form action="{{ route('stores.transfers.store', $store) }}" method="POST" class="space-y-8">
            @csrf
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden p-8 md:p-10 space-y-8">
                <!-- Basic Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Source Store</label>
                        <div
                            class="w-full px-4 py-3 rounded-xl bg-slate-50 border border-slate-100 text-slate-900 font-bold">
                            {{ $store->name }}
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="to_store_id"
                            class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Destination
                            Store</label>
                        <select name="to_store_id" id="to_store_id" required
                            class="block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all hover:border-slate-300">
                            <option value="">Select a store...</option>
                            @foreach($otherStores as $otherStore)
                                <option value="{{ $otherStore->id }}">{{ $otherStore->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Products Selection -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-slate-900">Transfer Items</h2>
                        <button type="button" onclick="addItem()"
                            class="text-xs font-bold text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-lg transition-colors">
                            + Add Item
                        </button>
                    </div>

                    <div id="items-container" class="space-y-4">
                        <div
                            class="item-row grid grid-cols-12 gap-4 items-end bg-slate-50/50 p-4 rounded-2xl border border-dashed border-slate-200">
                            <div class="col-span-7 space-y-2">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product</label>
                                <select name="items[0][product_id]" required onchange="updateMaxQty(this)"
                                    class="product-select block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all hover:border-slate-300">
                                    <option value="">Select product...</option>
                                    @foreach($products as $sp)
                                        <option value="{{ $sp->product_id }}" data-stock="{{ $sp->stock }}">
                                            {{ $sp->product->name }} (Stock: {{ $sp->stock }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-span-4 space-y-2">
                                <label
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest">Quantity</label>
                                <input type="number" name="items[0][quantity]" required min="1"
                                    class="qty-input block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all hover:border-slate-300">
                            </div>
                            <div class="col-span-1 flex justify-center pb-2">
                                <button type="button" onclick="removeItem(this)"
                                    class="text-slate-300 hover:text-rose-500 transition-colors">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div class="space-y-2">
                    <label for="notes" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Transfer
                        Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all hover:border-slate-300"
                        placeholder="Reason for transfer or other notes..."></textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-50">
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-10 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                        Create Transfer
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        let itemCount = 1;
        function addItem() {
            const container = document.getElementById('items-container');
            const newRow = container.firstElementChild.cloneNode(true);

            // Update names
            newRow.querySelector('.product-select').name = `items[${itemCount}][product_id]`;
            newRow.querySelector('.qty-input').name = `items[${itemCount}][quantity]`;

            // Reset values
            newRow.querySelector('.product-select').value = '';
            newRow.querySelector('.qty-input').value = '';
            newRow.querySelector('.qty-input').removeAttribute('max');

            container.appendChild(newRow);
            itemCount++;
        }

        function removeItem(btn) {
            const container = document.getElementById('items-container');
            if (container.childElementCount > 1) {
                btn.closest('.item-row').remove();
            }
        }

        function updateMaxQty(select) {
            const stock = select.options[select.selectedIndex].dataset.stock;
            const qtyInput = select.closest('.item-row').querySelector('.qty-input');
            if (stock) {
                qtyInput.max = stock;
            } else {
                qtyInput.removeAttribute('max');
            }
        }
    </script>
@endsection