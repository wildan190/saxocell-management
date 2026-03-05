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
                @php
                    $status = $transfer->status;
                    $statusLabel = 'Pending';
                    $statusColor = 'bg-slate-50 text-slate-700 ring-slate-600/20';
                    if ($status == 'shipping') {
                        $statusLabel = 'Shipping';
                        $statusColor = 'bg-blue-50 text-blue-700 ring-blue-600/20';
                    } elseif ($status == 'arrived') {
                        $statusLabel = 'Arrived';
                        $statusColor = 'bg-amber-50 text-amber-700 ring-amber-600/20';
                    } elseif ($status == 'received') {
                        $statusLabel = 'Received';
                        $statusColor = 'bg-emerald-50 text-emerald-700 ring-emerald-600/20';
                    }
                @endphp
                <span
                    class="inline-flex items-center rounded-full px-3 py-1 text-sm font-bold {{ $statusColor }} ring-1 ring-inset">
                    {{ $statusLabel }}
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

                @if($transfer->shipping_number)
                    <div class="bg-blue-50/50 rounded-3xl shadow-sm ring-1 ring-blue-100 p-8 space-y-4">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                            </svg>
                            <h3 class="text-xs font-black text-blue-600 uppercase tracking-widest">Shipping Info</h3>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Resi Code</p>
                            <p class="text-sm font-mono font-bold text-slate-900">{{ $transfer->shipping_number }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Shipping Cost</p>
                            <p class="text-sm font-bold text-slate-900">Rp
                                {{ number_format($transfer->shipping_cost, 0, ',', '.') }}</p>
                        </div>
                    </div>
                @endif

                @if($transfer->notes)
                    <div class="bg-indigo-50/50 rounded-3xl p-8 border border-indigo-100/50">
                        <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Notes</p>
                        <p class="text-sm text-indigo-700 leading-relaxed">{{ $transfer->notes }}</p>
                    </div>
                @endif

                @if($status != 'received')
                    <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 p-8 space-y-6">
                        <h3 class="text-sm font-bold text-slate-900 uppercase tracking-tight">Update Status</h3>

                        @if($status == 'pending')
                            <form action="{{ route('inventory.warehouse-to-store.ship', $transfer) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Shipping
                                        Number (Resi)</label>
                                    <input type="text" name="shipping_number"
                                        class="block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="Input Resi...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Shipping
                                        Cost (Ongkir)</label>
                                    <input type="number" name="shipping_cost"
                                        class="block w-full rounded-xl border-slate-200 text-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        placeholder="0">
                                </div>
                                <button type="submit"
                                    class="w-full py-3 rounded-xl bg-blue-600 text-white text-sm font-bold shadow-lg shadow-blue-100 hover:bg-blue-700 transition-all">
                                    Send Shipment
                                </button>
                            </form>
                        @elseif($status == 'shipping')
                            <form action="{{ route('inventory.warehouse-to-store.arrive', $transfer) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl bg-amber-500 text-white text-sm font-bold shadow-lg shadow-amber-100 hover:bg-amber-600 transition-all">
                                    Mark as Arrived
                                </button>
                            </form>
                        @elseif($status == 'arrived')
                            <form action="{{ route('inventory.warehouse-to-store.receive', $transfer) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full py-3 rounded-xl bg-emerald-600 text-white text-sm font-black shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all">
                                    Confirm Received (at Store)
                                </button>
                            </form>
                        @endif
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