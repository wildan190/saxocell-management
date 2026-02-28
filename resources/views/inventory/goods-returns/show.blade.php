@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8 pb-12">
        <div class="sm:flex sm:items-center justify-between">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('inventory.goods-receipts.index', $goodsReturn->goodsReceipt->warehouse) }}"
                                class="hover:text-slate-700">Goods Receipts</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">{{ $goodsReturn->return_number }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold text-slate-900">Return Details</h1>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('inventory.goods-returns.pdf', [$goodsReturn->goodsReceipt->warehouse, $goodsReturn]) }}"
                    class="rounded-xl bg-white border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 inline-flex items-center transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Download PDF
                </a>

                @php
                    $waMessage = "Hello, I am from " . config('app.name') . ". Regarding Good Receipt #" . $goodsReturn->goodsReceipt->receipt_number . ", we have rejected some items (Return #" . $goodsReturn->return_number . "). Reason: " . $goodsReturn->reason . ". Resolution: " . $goodsReturn->resolution . ". Details: " . route('inventory.goods-returns.show', [$goodsReturn->goodsReceipt->warehouse, $goodsReturn]);
                    $waUrl = "https://wa.me/?text=" . urlencode($waMessage);
                @endphp

                <a href="{{ $waUrl }}" target="_blank"
                    class="rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 inline-flex items-center transition-all">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.246 2.248 3.484 5.232 3.484 8.412 0 6.556-5.338 11.891-11.893 11.891-2.01 0-3.99-.513-5.747-1.487l-6.143 1.696zm4.908-4.52l.352.209c1.512.898 3.253 1.373 5.034 1.373h.001c5.454 0 9.897-4.443 9.897-9.897 0-2.641-1.028-5.124-2.895-6.992-1.866-1.867-4.348-2.892-6.989-2.892-5.454 0-9.897 4.443-9.897 9.897 0 1.914.549 3.784 1.587 5.39l.23.359-1.01 3.693 3.785-1.041zm11.724-6.848c-.287-.143-1.693-.836-1.954-.931-.261-.095-.451-.143-.641.143-.19.286-.735.931-.899 1.13-.164.199-.332.225-.618.082-.286-.143-1.21-.446-2.304-1.422-.851-.759-1.425-1.695-1.591-1.981-.166-.286-.018-.441.125-.583.128-.128.286-.334.429-.501.143-.167.19-.286.286-.477.095-.19.048-.358-.024-.501-.071-.143-.641-1.543-.878-2.114-.231-.554-.467-.478-.641-.486-.166-.007-.356-.008-.546-.008-.19 0-.5.071-.76.358-.261.286-.997.975-.997 2.381 0 1.406 1.022 2.766 1.164 2.957.143.19 2.012 3.073 4.873 4.308.68.294 1.211.469 1.625.6.682.217 1.303.186 1.794.113.547-.081 1.693-.691 1.931-1.358.237-.668.237-1.24.166-1.358-.071-.118-.261-.19-.547-.333z" />
                    </svg>
                    Send to Supplier
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                        <h2 class="text-lg font-bold text-slate-900">Items Returned</h2>
                    </div>
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-white">
                            <tr>
                                <th
                                    class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-3 py-4 text-right text-xs font-bold text-slate-500 uppercase tracking-wider text-right pr-6">
                                    Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($goodsReturn->items as $item)
                                <tr>
                                    <td class="py-4 pl-6 pr-3">
                                        <div class="text-sm font-medium text-slate-900">{{ $item->product->name }}</div>
                                        <div class="text-xs text-slate-500 font-mono">{{ $item->product->sku }}</div>
                                    </td>
                                    <td class="px-3 py-4 text-right pr-6 font-bold text-slate-900">
                                        {{ $item->quantity }} {{ $item->product->unit }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if(!empty($goodsReturn->proof_photos))
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                        <h2 class="text-lg font-bold text-slate-900 mb-6 uppercase tracking-wider text-xs">Proof Photos</h2>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach($goodsReturn->proof_photos as $photo)
                                <a href="{{ asset('storage/' . $photo) }}" target="_blank"
                                    class="block group relative aspect-square rounded-xl overflow-hidden border border-slate-100 shadow-sm">
                                    <img src="{{ asset('storage/' . $photo) }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                    <div
                                        class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="space-y-8">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 space-y-6">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status</p>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                            {{ ucfirst($goodsReturn->status) }}
                        </span>
                    </div>
                    <div class="space-y-1 pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Reason</p>
                        <p class="text-sm text-slate-600 leading-relaxed">{{ $goodsReturn->reason }}</p>
                    </div>
                    <div class="space-y-1 pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Requested Resolution</p>
                        <p class="text-sm font-semibold text-indigo-600">{{ $goodsReturn->resolution }}</p>
                        @if($goodsReturn->resolution === 'Refund' && $goodsReturn->adjusted_price)
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mt-2">Adjusted Price</p>
                            <p class="text-sm font-bold text-emerald-600">Rp
                                {{ number_format($goodsReturn->adjusted_price, 2) }}</p>
                        @endif
                    </div>
                    <div class="space-y-1 pt-4 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Original Receipt</p>
                        <a href="{{ route('inventory.goods-receipts.show', [$goodsReturn->goodsReceipt->warehouse, $goodsReturn->goodsReceipt]) }}"
                            class="text-sm font-medium text-slate-900 hover:text-indigo-600 transition-colors">
                            {{ $goodsReturn->goodsReceipt->receipt_number }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection