@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('inventory.transfers.index') }}" class="hover:text-slate-700">Stock
                                Transfers</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">New Transfer</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Create Stock Transfer</h1>
                <p class="mt-2 text-sm text-slate-700">Move products between warehouses. Stock will be validated before
                    processing.</p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-xl bg-red-50 p-4 border border-red-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">There were errors with your submission</h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc space-y-1 pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('inventory.transfers.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">From Warehouse</label>
                        <select name="from_warehouse_id" required
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                            <option value="" disabled selected>Select Source</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-center pt-6">
                        <svg class="h-6 w-6 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">To Warehouse</label>
                        <select name="to_warehouse_id" required
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                            <option value="" disabled selected>Select Destination</option>
                            @foreach($warehouses as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 pt-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Transfer Date</label>
                        <input type="date" name="transfer_date" required value="{{ date('Y-m-d') }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Notes</label>
                        <input type="text" name="notes" placeholder="Optional transfer reason..."
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                    </div>
                </div>

                <div class="border-t border-slate-100 pt-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Products to Transfer</h3>
                        <button type="button" onclick="addItemRow()"
                            class="inline-flex items-center px-3 py-1.5 text-sm font-semibold text-indigo-600 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Product
                        </button>
                    </div>

                    <div id="items-container" class="space-y-4">
                        <div
                            class="item-row grid grid-cols-1 gap-4 sm:grid-cols-12 items-end bg-slate-50/50 p-4 rounded-xl border border-slate-100">
                            <div class="sm:col-span-8">
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Product</label>
                                <select name="items[0][product_id]" required
                                    class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                    <option value="" disabled selected>Select item</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }}
                                            ({{ $product->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="sm:col-span-3">
                                <label
                                    class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Qty</label>
                                <input type="number" name="items[0][quantity]" required step="1" min="1" placeholder="0"
                                    class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                            </div>
                            <div class="sm:col-span-1 flex justify-center pb-1">
                                <button type="button" onclick="this.closest('.item-row').remove()"
                                    class="text-slate-400 hover:text-red-600 transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('inventory.transfers.index') }}"
                        class="px-6 py-2 text-sm font-semibold text-slate-700">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-slate-900 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all">
                        Process Transfer
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
                newRow.className = 'item-row grid grid-cols-1 gap-4 sm:grid-cols-12 items-end bg-slate-50/50 p-4 rounded-xl border border-slate-100';
                newRow.innerHTML = `
                                            <div class="sm:col-span-8">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Product</label>
                                                <select name="items[${rowCount}][product_id]" required
                                                    class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                                    <option value="" disabled selected>Select item</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}">{{ $product->sku }} - {{ $product->name }} ({{ $product->unit }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="sm:col-span-3">
                                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Qty</label>
                                                <input type="number" name="items[${rowCount}][quantity]" required step="1" min="1" placeholder="0"
                                                    class="block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm text-center transition-all">
                                            </div>
                                            <div class="sm:col-span-1 flex justify-center pb-1">
                                                <button type="button" onclick="this.closest('.item-row').remove()" class="text-slate-400 hover:text-red-600 transition-colors">
                                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </div>
                                        `;
                container.appendChild(newRow);
                rowCount++;
            }
        </script>
    @endpush
@endsection