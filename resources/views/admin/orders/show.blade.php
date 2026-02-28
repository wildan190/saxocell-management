@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-12 pb-24">
        <!-- Header -->
        <div
            class="lg:flex lg:items-center lg:justify-between p-10 bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 border-l-8 border-indigo-600">
            <div class="min-w-0 flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('admin.orders.index') }}"
                                class="hover:text-slate-900 transition-colors font-medium">Orders</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-bold text-slate-900">{{ $order->order_number }}</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-6">
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">{{ $order->order_number }}</h2>
                    <span class="inline-flex items-center rounded-full px-4 py-1.5 text-xs font-black uppercase tracking-widest
                            {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                            {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-700' : '' }}
                            {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}">
                        {{ $order->status }}
                    </span>
                </div>
            </div>

            @php
                $waNumber = preg_replace('/[^0-9]/', '', $order->customer_contact);
                if (str_starts_with($waNumber, '0'))
                    $waNumber = '62' . substr($waNumber, 1);
                $message = urlencode("Hi {$order->customer_name}, your order {$order->order_number} has been updated to {$order->status}.");
                $waUrl = "https://wa.me/{$waNumber}?text={$message}";
            @endphp

            <div class="mt-8 flex flex-wrap gap-4 lg:mt-0">
                <a href="{{ $waUrl }}" target="_blank"
                    class="inline-flex items-center rounded-2xl bg-green-500 px-6 py-3 text-sm font-black text-white shadow-xl hover:bg-green-600 transition-all active:scale-95 gap-3">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                    </svg>
                    WhatsApp Customer
                </a>

                @if($order->status === 'pending')
                    <form action="{{ route('admin.orders.confirm', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-2xl bg-indigo-600 px-6 py-3 text-sm font-black text-white shadow-xl hover:bg-indigo-700 transition-all active:scale-95 uppercase tracking-widest">
                            Confirm Payment
                        </button>
                    </form>
                @elseif($order->status === 'confirmed')
                    <button type="button" onclick="document.getElementById('shipModal').classList.remove('hidden')"
                        class="inline-flex items-center rounded-2xl bg-slate-900 px-6 py-3 text-sm font-black text-white shadow-xl hover:bg-slate-800 transition-all active:scale-95 uppercase tracking-widest">
                        Ready to Ship
                    </button>
                @elseif($order->status === 'shipped')
                    <form action="{{ route('admin.orders.complete', $order) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center rounded-2xl bg-green-600 px-6 py-3 text-sm font-black text-white shadow-xl hover:bg-green-700 transition-all active:scale-95 uppercase tracking-widest">
                            Mark Completed
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-8">
                <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                        <h3 class="text-xl font-black text-slate-900 tracking-tight">Order Items</h3>
                    </div>
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead>
                            <tr class="bg-white">
                                <th
                                    class="py-4 px-8 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Product</th>
                                <th
                                    class="py-4 px-8 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Qty</th>
                                <th
                                    class="py-4 px-8 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($order->items as $item)
                                <tr>
                                    <td class="py-6 px-8 flex items-center gap-4">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900">{{ $item->product->name }}</span>
                                            <span
                                                class="text-xs font-mono text-slate-400 uppercase">{{ $item->product->sku }}</span>
                                        </div>
                                    </td>
                                    <td class="py-6 px-8 text-center font-bold text-slate-700 text-sm">{{ $item->quantity }}
                                    </td>
                                    <td class="py-6 px-8 text-right font-black text-slate-900">Rp
                                        {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-slate-50/50">
                            <tr>
                                <td colspan="2" class="py-6 px-8 text-right font-bold text-slate-500 text-sm">Grand Total
                                </td>
                                <td class="py-6 px-8 text-right font-black text-2xl text-slate-900">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Proof of Payment -->
                <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 p-8 space-y-6">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">Proof of Payment</h3>
                    <div class="rounded-3xl overflow-hidden ring-1 ring-slate-100 shadow-lg group relative cursor-zoom-in"
                        onclick="window.open('{{ asset('storage/' . $order->proof_of_transfer_path) }}', '_blank')">
                        <img src="{{ asset('storage/' . $order->proof_of_transfer_path) }}"
                            class="w-full object-cover transition-transform group-hover:scale-105">
                        <div
                            class="absolute inset-0 bg-slate-900/0 group-hover:bg-slate-900/10 transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                            <span class="bg-white px-4 py-2 rounded-full font-bold text-xs text-slate-900 shadow-xl">Click
                                to Enlarge</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer & Shipping Sidebar -->
            <div class="space-y-8 h-fit">
                <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 p-8 space-y-10">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Customer Insight</h4>
                        <div>
                            <p class="text-xl font-black text-slate-900 tracking-tight">{{ $order->customer_name }}</p>
                            <p class="text-sm font-bold text-indigo-600">{{ $order->customer_contact }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 pt-10 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Shipping Destination
                        </h4>
                        <p class="text-sm font-bold text-slate-700 leading-relaxed">{{ $order->customer_address }}</p>
                    </div>

                    @if($order->resi_number)
                        <div class="space-y-4 pt-10 border-t border-slate-50">
                            <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest text-indigo-500">Tracking
                                Info (Resi)</h4>
                            <p class="text-lg font-black text-slate-900 tracking-widest uppercase">{{ $order->resi_number }}</p>
                        </div>
                    @endif

                    <div class="space-y-4 pt-10 border-t border-slate-50">
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Fulfillment Store</h4>
                        <p class="text-sm font-black text-slate-900">{{ $order->store->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ship Modal -->
    <div id="shipModal" class="hidden fixed inset-0 z-50 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                onclick="document.getElementById('shipModal').classList.add('hidden')"></div>
            <div
                class="relative transform overflow-hidden rounded-[40px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <form action="{{ route('admin.orders.ship', $order) }}" method="POST" class="p-10 space-y-6">
                    @csrf
                    <div class="text-center space-y-2">
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight">Enter Resi Number</h3>
                        <p class="text-slate-500 font-medium text-sm px-4">Provide the tracking code from the courier to
                            notify the customer about shipment.</p>
                    </div>
                    <div>
                        <input type="text" name="resi_number" required placeholder="e.g. JNE12345678"
                            class="block w-full rounded-2xl border-slate-200 py-4 px-6 text-center text-lg font-black tracking-widest text-slate-900 focus:ring-indigo-600 focus:border-indigo-600 shadow-sm uppercase placeholder:normal-case placeholder:font-medium placeholder:tracking-normal">
                    </div>
                    <div class="flex gap-4 pt-4">
                        <button type="button" onclick="document.getElementById('shipModal').classList.add('hidden')"
                            class="flex-1 py-4 text-sm font-bold text-slate-500">Discard</button>
                        <button type="submit"
                            class="flex-[2] rounded-2xl bg-indigo-600 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-700 transition-all active:scale-[0.98]">Confirm
                            Shipment</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection