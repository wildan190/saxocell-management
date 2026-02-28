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
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">{{ $goodsReceipt->receipt_number }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Receipt Details</h1>
                <p class="mt-2 text-sm text-slate-700">Detailed summary of goods received at <span
                        class="font-semibold text-indigo-600">{{ $warehouse->name }}</span>.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Receipt Number</p>
                        <p class="text-lg font-black text-indigo-600">{{ $goodsReceipt->receipt_number }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Received Date</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->received_date }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sender / Vendor</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->sender_name }}</p>
                    </div>
                </div>
                @if($goodsReceipt->notes)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Notes</p>
                        <p class="text-sm text-slate-600 italic">"{{ $goodsReceipt->notes }}"</p>
                    </div>
                @endif
            </div>

            <div class="p-0">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-white">
                        <tr>
                            <th scope="col"
                                class="py-4 pl-8 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Product SKU</th>
                            <th scope="col"
                                class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Product Name</th>
                            <th scope="col"
                                class="px-3 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Quantity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($goodsReceipt->items as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors text-sm">
                                <td class="whitespace-nowrap py-4 pl-8 pr-3 font-mono font-medium text-indigo-600">
                                    {{ $item->product->sku }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-600">
                                    {{ $item->product->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-right font-bold text-slate-900">
                                    {{ number_format($item->quantity, 2) }} {{ $item->product->unit }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-end space-x-3">
                @if($goodsReceipt->returns->count() > 0)
                    @php $firstReturn = $goodsReceipt->returns->first(); @endphp
                    <div class="flex items-center space-x-4 pr-4 border-r border-slate-200">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rejection Status:</span>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            {{ ucfirst($firstReturn->status) }}
                        </span>
                    </div>
                    <a href="{{ route('inventory.goods-returns.show', [$warehouse, $firstReturn]) }}"
                        class="rounded-xl bg-amber-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 transition-all">
                        View Rejection Details
                    </a>
                @else
                    <a href="{{ route('inventory.goods-returns.create', [$warehouse, $goodsReceipt]) }}"
                        class="rounded-xl bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-all">
                        Return Goods / Rejected
                    </a>
                @endif
                <a href="{{ route('inventory.goods-receipts.index', $warehouse) }}"
                    class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all">
                    Back to List
                </a>
            </div>
        </div>
    </div>
@endsection