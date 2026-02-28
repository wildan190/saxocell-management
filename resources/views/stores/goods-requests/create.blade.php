@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('stores.show', $store) }}" class="hover:text-slate-700">{{ $store->name }}</a>
                        </li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('stores.goods-requests.index', $store) }}" class="hover:text-slate-700">Goods
                                Requests</a></li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Request Goods</h1>
                <p class="mt-2 text-sm text-slate-700">Submit a request for stock from a central warehouse.</p>
            </div>
        </div>

        <form action="{{ route('stores.goods-requests.store', $store) }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-6">
                <!-- Warehouse Selection -->
                <div>
                    <label class="block text-sm font-semibold text-slate-900">Select Source Warehouse</label>
                    <div class="mt-2 grid grid-cols-1 gap-4 sm:grid-cols-2">
                        @foreach($warehouses as $warehouse)
                            <label
                                class="relative flex cursor-pointer rounded-xl border border-slate-200 p-4 shadow-sm focus:outline-none hover:bg-slate-50 transition-colors">
                                <input type="radio" name="warehouse_id" value="{{ $warehouse->id }}" class="sr-only peer"
                                    required>
                                <span class="flex flex-1">
                                    <span class="flex flex-col">
                                        <span class="block text-sm font-bold text-slate-900">{{ $warehouse->name }}</span>
                                        <span class="mt-1 flex items-center text-xs text-slate-500">
                                            {{ $warehouse->location }}
                                        </span>
                                    </span>
                                </span>
                                <svg class="h-5 w-5 text-indigo-600 opacity-0 peer-checked:opacity-100 transition-opacity"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span
                                    class="pointer-events-none absolute -inset-px rounded-xl border-2 border-transparent peer-checked:border-indigo-600"
                                    aria-hidden="true"></span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-900">Request Notes</label>
                    <textarea name="notes" rows="2" placeholder="Urgency, specific instructions, etc..."
                        class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"></textarea>
                </div>

                <div class="border-t border-slate-100 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Requested Items</h3>
                        <button type="button" onclick="addItemRow()"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-xl hover:bg-indigo-100 transition-colors shadow-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Item
                        </button>
                    </div>

                    <div id="items-container" class="space-y-4">
                        <!-- Initial Row -->
                        <div class="item-row bg-slate-50/50 p-5 rounded-2xl border border-slate-100">
                            <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 items-end">
                                <div class="lg:col-span-8">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product</label>
                                    <select name="items[0][product_id]" required
                                        class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                        <option value="">Select a product...</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="lg:col-span-3">
                                    <label
                                        class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                    <input type="number" name="items[0][quantity]" required min="1" placeholder="0"
                                        class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                                </div>
                                <div class="lg:col-span-1 flex justify-center pb-1">
                                    <button type="button" onclick="this.closest('.item-row').remove()"
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

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('stores.goods-requests.index', $store) }}"
                        class="px-6 py-2 text-sm font-semibold text-slate-700">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all shadow-indigo-200">
                        Submit Request
                    </button>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let rowCount = 1;
            function addItemRow() {
                const container = document.getElementById('items-container');
                const newRow = document.createElement('div');
                newRow.className = 'item-row bg-slate-50/50 p-5 rounded-2xl border border-slate-100';

                newRow.innerHTML = `
                                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-12 items-end">
                                        <div class="lg:col-span-8">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Product</label>
                                            <select name="items[${rowCount}][product_id]" required class="block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm">
                                                <option value="">Select a product...</option>
                                                @foreach($products as $product)
                                                    <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->sku }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="lg:col-span-3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Quantity</label>
                                            <input type="number" name="items[${rowCount}][quantity]" required min="1" placeholder="0" class="block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center">
                                        </div>
                                        <div class="lg:col-span-1 flex justify-center pb-1">
                                            <button type="button" onclick="this.closest('.item-row').remove()" class="text-slate-300 hover:text-red-500 transition-colors">
                                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </div>
                                    </div>
                                `;
                container.appendChild(newRow);
                rowCount++;
            }
        </script>
    @endpush
@endsection