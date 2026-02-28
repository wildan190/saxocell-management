@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header Area -->
        <div
            class="lg:flex lg:items-center lg:justify-between p-8 bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 border-l-4 border-amber-600">
            <div class="min-w-0 flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('warehouses.show', $warehouse) }}"
                                class="hover:text-slate-700">{{ $warehouse->name }}</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('warehouses.incoming-requests.index', $warehouse) }}"
                                class="hover:text-slate-700">Incoming Requests</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">{{ $goodsRequest->request_number }}</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-4">
                    <h2 class="text-3xl font-black leading-7 text-slate-900 tracking-tight">
                        {{ $goodsRequest->request_number }}
                    </h2>
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-black uppercase tracking-wider
                                {{ $goodsRequest->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $goodsRequest->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                {{ $goodsRequest->status === 'shipped' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                {{ $goodsRequest->status === 'received' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ $goodsRequest->status }}
                    </span>
                </div>
            </div>

            <div class="mt-5 lg:ml-4 lg:mt-0 flex gap-3">
                @if($goodsRequest->status === 'pending')
                    <form action="{{ route('warehouses.incoming-requests.confirm', [$warehouse, $goodsRequest]) }}"
                        method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-blue-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 transition-all active:scale-95">
                            Confirm Request
                        </button>
                    </form>
                @elseif($goodsRequest->status === 'confirmed')
                    <form action="{{ route('warehouses.incoming-requests.ship', [$warehouse, $goodsRequest]) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-xl bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-95">
                            Process Shipment
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="md:col-span-2 space-y-8">
                <!-- Items Table -->
                <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50">
                        <h3 class="text-lg font-bold text-slate-900">Requested Items</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="py-4 px-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Current Stock</th>
                                <th class="py-4 px-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Requested Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($goodsRequest->items as $item)
                                @php
                                    $inventory = $warehouse->inventories()->where('product_id', $item->product_id)->first();
                                    $currentStock = $inventory ? $inventory->quantity : 0;
                                    $isSufficient = $currentStock >= $item->quantity;
                                @endphp
                                <tr
                                    class="{{ !$isSufficient && $goodsRequest->status !== 'received' && $goodsRequest->status !== 'shipped' ? 'bg-red-50/50' : '' }}">
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800">{{ $item->product->name }}</span>
                                            <span
                                                class="text-[10px] font-mono text-slate-400 uppercase">{{ $item->product->sku }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span class="text-sm font-bold {{ $isSufficient ? 'text-slate-600' : 'text-red-600' }}">
                                            {{ number_format($currentStock, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-4 px-6 text-right font-black text-slate-900">
                                        {{ number_format($item->quantity, 0, ',', '.') }}
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter ml-1">{{ $item->product->unit }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($goodsRequest->notes)
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 p-6">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">Store Notes</h3>
                        <p class="text-slate-700">{{ $goodsRequest->notes }}</p>
                    </div>
                @endif
            </div>

            <div class="space-y-8">
                <!-- Request Info -->
                <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 p-6 space-y-6">
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Requesting Store
                        </h4>
                        <p class="text-sm font-bold text-slate-900">{{ $goodsRequest->store->name }}</p>
                        <p class="text-xs text-slate-500">{{ $goodsRequest->store->address }}</p>
                    </div>
                    <div class="pt-6 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Requested At</h4>
                        <p class="text-sm font-bold text-slate-900">{{ $goodsRequest->created_at->format('M d, Y') }}</p>
                        <p class="text-xs text-slate-500">{{ $goodsRequest->created_at->format('H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection