@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <div class="sm:flex sm:items-center">
        <div class="sm:flex-auto">
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Inventory Overview</h1>
            <p class="mt-2 text-sm text-slate-700">Centralized view of product availability across all warehouse locations.</p>
        </div>
        <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none flex gap-3">
            <a href="{{ route('inventory.opname.index') }}"
                class="block rounded-xl bg-indigo-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">
                Stock Opname
            </a>
            <a href="{{ route('inventory.transfers.create') }}"
                class="block rounded-xl bg-slate-100 px-6 py-3 text-center text-sm font-semibold text-slate-600 hover:bg-slate-200 transition-all">
                New Internal Transfer
            </a>
        </div>
    </div>

    <div class="bg-white shadow-sm ring-1 ring-slate-100 rounded-2xl overflow-hidden mt-8">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-bold text-slate-900 sticky left-0 bg-slate-50 z-10 border-r border-slate-200">Product</th>
                        @foreach($warehouses as $warehouse)
                            <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-slate-900 border-r border-slate-100">{{ $warehouse->name }}</th>
                        @endforeach
                        <th scope="col" class="px-3 py-3.5 text-center text-sm font-bold text-indigo-600">Total Stock</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900 sticky left-0 bg-white z-10 border-r border-slate-200">
                                <div class="flex flex-col">
                                    <span class="font-bold">{{ $product->name }}</span>
                                    <span class="text-xs text-slate-500 font-mono">{{ $product->sku }}</span>
                                </div>
                            </td>
                            @php $totalStock = 0; @endphp
                            @foreach($warehouses as $warehouse)
                                @php 
                                    $inv = $product->inventories->where('warehouse_id', $warehouse->id)->first();
                                    $qty = $inv ? $inv->quantity : 0;
                                    $totalStock += $qty;
                                @endphp
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-center {{ $qty > 0 ? 'text-slate-900 font-medium' : 'text-slate-300' }} border-r border-slate-100">
                                    {{ $qty > 0 ? number_format($qty, 0, ',', '.') : '-' }}
                                </td>
                            @endforeach
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-center font-black text-indigo-600">
                                {{ number_format($totalStock, 0, ',', '.') }}
                                <span class="text-[10px] font-normal text-slate-400 ml-0.5 uppercase tracking-tighter">{{ $product->unit }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $warehouses->count() + 2 }}" class="py-12 text-center text-slate-500 italic">No products in inventory yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
