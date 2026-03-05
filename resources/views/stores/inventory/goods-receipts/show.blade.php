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
                        <li><a href="{{ route('stores.inventory.goods-receipts.index', $store) }}"
                                class="hover:text-slate-700">Goods Receipts</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">{{ $goodsReceipt->receipt_number }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Store Receipt Details</h1>
                <p class="mt-2 text-sm text-slate-700">Detailed summary of goods received at Store <span
                        class="font-semibold text-indigo-600">{{ $store->name }}</span>.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-5 text-center sm:text-left">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Receipt Number</p>
                        <p class="text-lg font-black text-indigo-600">{{ $goodsReceipt->receipt_number }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Received Date</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->received_date }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Responsible Admin</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->admin->name ?? 'System' }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sender / Vendor</p>
                        <p class="text-lg font-bold text-slate-900">{{ $goodsReceipt->sender_name }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Admin Fee</p>
                        <p class="text-lg font-bold text-slate-900">Rp
                            {{ number_format($goodsReceipt->admin_fee, 0, ',', '.') }}</p>
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
                                Purchase Price</th>
                            <th scope="col"
                                class="px-3 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Quantity</th>
                            <th scope="col"
                                class="px-8 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @php $grandTotal = 0; @endphp
                        @foreach($goodsReceipt->items as $item)
                            @php
                                $subtotal = $item->quantity * $item->purchase_price;
                                $grandTotal += $subtotal;
                            @endphp
                            <tr class="hover:bg-slate-50/30 transition-colors text-sm">
                                <td
                                    class="whitespace-nowrap py-4 pl-8 pr-3 font-mono font-medium text-indigo-600 border-l-4 border-indigo-600">
                                    {{ $item->product->sku }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-600 font-medium">
                                    {{ $item->product->name }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-right text-slate-600 font-bold">
                                    Rp {{ number_format($item->purchase_price, 0, ',', '.') }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-right font-bold text-slate-900">
                                    {{ number_format($item->quantity, 0, ',', '.') }} {{ $item->product->unit }}
                                </td>
                                <td class="whitespace-nowrap px-8 py-4 text-right font-black text-indigo-600">
                                    Rp {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-indigo-50/30">
                        <tr>
                            <td colspan="4"
                                class="py-6 pl-8 text-right text-sm font-bold text-slate-500 uppercase tracking-widest">Total
                                Bayar</td>
                            <td class="py-6 px-8 text-right text-xl font-black text-indigo-700">Rp
                                {{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="p-8 bg-slate-50/50 border-t border-slate-100 flex justify-end space-x-3">
                <a href="{{ route('stores.inventory.goods-receipts.index', $store) }}"
                    class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all">
                    Back to List
                </a>
            </div>
        </div>
    </div>
@endsection
