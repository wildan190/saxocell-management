@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 pb-20">
        <!-- Header -->
        <div class="sm:flex sm:items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <a href="{{ route('stores.transfers.index', $store) }}"
                        class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900">{{ $transfer->transfer_number }}</h1>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status:</span>
                    @php
                        $statusColors = [
                            'pending' => 'bg-amber-100 text-amber-700',
                            'shipped' => 'bg-blue-100 text-blue-700',
                            'received' => 'bg-green-100 text-green-700',
                        ];
                    @endphp
                    <span class="inline-flex items-center rounded-full px-4 py-1 text-xs font-black uppercase tracking-wider {{ $statusColors[$transfer->status] ?? 'bg-slate-100 text-slate-700' }}">
                        {{ $transfer->status }}
                    </span>
                </div>
            </div>
            
            <div class="flex gap-3">
                @if($transfer->status === 'pending' && $transfer->from_store_id == $store->id)
                    <form action="{{ route('stores.transfers.ship', [$store, $transfer]) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-blue-600 px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-blue-100 hover:bg-blue-500 transition-all active:scale-[0.98]">
                            Ship Transfer
                        </button>
                    </form>
                @endif

                @if($transfer->status === 'shipped' && $transfer->to_store_id == $store->id)
                    <form action="{{ route('stores.transfers.receive', [$store, $transfer]) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="rounded-xl bg-green-600 px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-green-100 hover:bg-green-500 transition-all active:scale-[0.98]">
                            Confirm Receipt
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100">
                <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="rounded-xl bg-rose-50 p-4 border border-rose-100">
                <p class="text-sm font-medium text-rose-800">{{ session('error') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Details Column -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Info Card -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 md:p-10 border-b border-slate-50 grid grid-cols-2 lg:grid-cols-3 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Source Store</p>
                            <p class="font-bold text-slate-900">{{ $transfer->fromStore->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Destination Store</p>
                            <p class="font-bold text-slate-900">{{ $transfer->toStore->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Requested Date</p>
                            <p class="font-bold text-slate-900">{{ $transfer->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($transfer->shipped_at)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Shipped Date</p>
                                <p class="font-bold text-slate-600">{{ $transfer->shipped_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        @if($transfer->received_at)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Received Date</p>
                                <p class="font-bold text-emerald-600">{{ $transfer->received_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Items Table -->
                    <div class="p-8 md:p-10">
                        <h2 class="text-lg font-bold text-slate-900 mb-6">Transferred Items</h2>
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr>
                                    <th class="py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product</th>
                                    <th class="py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Quantity</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($transfer->items as $item)
                                    <tr>
                                        <td class="py-4">
                                            <div class="space-y-0.5">
                                                <p class="text-sm font-bold text-slate-900">{{ $item->product->name }}</p>
                                                <p class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-widest">{{ $item->product->sku }}</p>
                                            </div>
                                        </td>
                                        <td class="py-4 text-right">
                                            <span class="text-sm font-black text-slate-900">{{ $item->quantity }} {{ $item->product->unit }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if($transfer->notes)
                        <div class="p-8 md:p-10 bg-slate-50/50 border-t border-slate-50">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Notes</p>
                            <p class="text-sm text-slate-600 italic leading-relaxed">"{{ $transfer->notes }}"</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar Column: Activity Logs -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-indigo-900 rounded-[2rem] p-8 text-indigo-100 shadow-xl shadow-indigo-100">
                    <h2 class="text-xl font-black mb-10 flex items-center gap-3">
                        <div class="h-8 w-8 rounded-lg bg-indigo-500/30 flex items-center justify-center">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        Activity Logs
                    </h2>

                    <div class="relative pl-8 space-y-10 before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-[2px] before:bg-indigo-500/30">
                        @foreach($transfer->logs as $log)
                            <div class="relative">
                                <div class="absolute -left-[27px] top-1.5 h-3.5 w-3.5 rounded-full bg-indigo-500 ring-4 ring-indigo-900/50"></div>
                                <div class="space-y-1">
                                    <div class="flex items-center justify-between">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-indigo-300">{{ $log->activity }}</p>
                                        <p class="text-[9px] font-bold text-indigo-500">{{ $log->created_at->format('H:i') }} WIB</p>
                                    </div>
                                    <p class="text-sm font-medium text-white">{{ $log->notes }}</p>
                                    <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest">By {{ $log->user->name }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 italic text-slate-500">
                    <p class="text-xs text-center leading-relaxed">
                        Transfers ensure stock accuracy across your retail locations. Always confirm receipts to finalize inventory updates.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
