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
                        <li class="font-medium text-slate-900">{{ $stockTransfer->transfer_number }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Transfer Details</h1>
                <p class="mt-2 text-sm text-slate-700">Summary of internal stock movement between warehouses.</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <div class="grid grid-cols-1 gap-8 sm:grid-cols-4 items-center">
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Transfer #</p>
                        <p class="text-lg font-black text-indigo-600">{{ $stockTransfer->transfer_number }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Date</p>
                        <p class="text-md font-bold text-slate-900">{{ $stockTransfer->transfer_date }}</p>
                    </div>
                    <div class="sm:col-span-2">
                        <div class="flex items-center gap-4">
                            <div class="flex flex-col">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Source</p>
                                <span
                                    class="inline-flex items-center rounded-md bg-red-50 px-3 py-1 text-sm font-semibold text-red-700 ring-1 ring-inset ring-red-600/10">
                                    {{ $stockTransfer->fromWarehouse->name }}
                                </span>
                            </div>
                            <svg class="h-5 w-5 text-slate-300 mt-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                            <div class="flex flex-col">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Destination</p>
                                <span
                                    class="inline-flex items-center rounded-md bg-green-50 px-3 py-1 text-sm font-semibold text-green-700 ring-1 ring-inset ring-green-600/10">
                                    {{ $stockTransfer->toWarehouse->name }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                @if($stockTransfer->notes)
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Notes</p>
                        <p class="text-sm text-slate-600 italic">"{{ $stockTransfer->notes }}"</p>
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
                                Transferred Qty</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @foreach($stockTransfer->items as $item)
                            <tr class="hover:bg-slate-50/30 transition-colors text-sm">
                                <td class="whitespace-nowrap py-4 pl-8 pr-3 font-mono font-medium text-indigo-600">
                                    {{ $item->product->sku }}
                                </td>
                                <td class="whitespace-nowrap px-3 py-4 text-slate-600">
                                    {{ $item->product->name }}
                                </td>
                                <td
                                    class="whitespace-nowrap px-3 py-4 text-right font-black text-slate-900 border-l border-slate-50">
                                    {{ number_format($item->quantity, 2) }} {{ $item->product->unit }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="p-8 bg-white border-t border-slate-100 flex justify-end">
                <a href="{{ route('inventory.transfers.index') }}"
                    class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all">Back
                    to List</a>
            </div>
        </div>
    </div>
@endsection