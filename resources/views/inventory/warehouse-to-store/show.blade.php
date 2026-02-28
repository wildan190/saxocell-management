@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center sm:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <a href="{{ route('inventory.warehouse-to-store.index') }}"
                        class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Transfer Details</h1>
                </div>
                <p class="mt-2 text-sm text-slate-500">Logistics record for stock movement <span
                        class="font-mono font-bold text-slate-900">{{ $transfer->transfer_number }}</span>.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <span
                    class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-sm font-bold text-green-700 ring-1 ring-inset ring-green-600/20">
                    Completed
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Left Column: Summary -->
            <div class="md:col-span-1 space-y-6">
                <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 p-8 space-y-6">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Transfer Number</p>
                        <p class="text-lg font-bold text-slate-900">{{ $transfer->transfer_number }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date</p>
                        <p class="text-sm font-semibold text-slate-700">{{ $transfer->transfer_date->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Origin</p>
                        <p class="text-sm font-bold text-orange-600">{{ $transfer->fromWarehouse->name }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Destination</p>
                        <p class="text-sm font-bold text-indigo-600">{{ $transfer->toStore->name }}</p>
                    </div>
                </div>

                @if($transfer->notes)
                    <div class="bg-indigo-50/50 rounded-3xl p-8 border border-indigo-100/50">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Notes</p>
                        <p class="text-sm text-indigo-700 leading-relaxed">{{ $transfer->notes }}</p>
                    </div>
                @endif
            </div>

            <!-- Right Column: Table -->
            <div class="md:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
                    <div class="px-8 py-5 border-b border-slate-50 bg-slate-50/30">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Transferred Items</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-white">
                                <th
                                    class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Product</th>
                                <th
                                    class="px-8 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Quantity</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($transfer->items as $item)
                                <tr class="hover:bg-slate-50/30 transition-colors">
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $item->product->name }}</span>
                                            <span class="text-xs text-slate-500 font-mono">{{ $item->product->sku }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <span
                                            class="inline-flex items-center rounded-lg bg-slate-100 px-3 py-1 text-sm font-bold text-slate-700">
                                            {{ $item->quantity }} {{ $item->product->unit }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection