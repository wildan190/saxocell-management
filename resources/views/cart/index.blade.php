@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-12 pb-24">
        <!-- Header -->
        <div class="px-4">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight">Your Cart</h1>
            <p class="text-slate-500 font-medium mt-1">Ready to complete your purchase?</p>
        </div>

        @if(empty($cart))
            <div class="text-center py-24 bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 mx-4">
                <div class="inline-flex items-center justify-center p-8 bg-slate-50 rounded-[32px] mb-6">
                    <svg class="h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" /></svg>
                </div>
                <h3 class="text-2xl font-black text-slate-900 italic">Your cart is empty.</h3>
                <p class="text-slate-500 font-medium mt-2">Explore our marketplace to find premium products.</p>
                <a href="{{ route('marketplace.index') }}" class="mt-8 inline-flex items-center rounded-full bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-95">
                    Start Shopping
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 px-4">
                <!-- Items List -->
                <div class="lg:col-span-2 space-y-4">
                    @php $total = 0; @endphp
                    @foreach($cart as $id => $item)
                        @php $subtotal = $item['price'] * $item['quantity']; $total += $subtotal; @endphp
                        <div class="bg-white rounded-[32px] p-6 shadow-sm ring-1 ring-slate-100 flex gap-6 items-center">
                            <div class="h-24 w-24 flex-shrink-0 rounded-2xl bg-slate-50 overflow-hidden ring-1 ring-slate-100">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full w-full flex items-center justify-center text-slate-200">
                                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-lg font-black text-slate-900 tracking-tight truncate">{{ $item['name'] }}</h3>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $item['sku'] }}</p>
                                    </div>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold text-slate-600">{{ $item['quantity'] }} Units</span>
                                        <span class="text-slate-300">|</span>
                                        <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg">{{ $item['store_name'] }}</span>
                                    </div>
                                    <p class="text-sm font-black text-slate-900">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Summary Card -->
                <div class="bg-slate-900 rounded-[40px] p-8 shadow-2xl h-fit space-y-8 sticky top-8">
                    <h3 class="text-xl font-black text-white tracking-tight">Order Summary</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between text-slate-400 font-medium">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-slate-400 font-medium pb-4 border-b border-slate-800">
                            <span>Shipping</span>
                            <span class="text-indigo-400">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between pt-4">
                            <span class="text-lg font-black text-white">Total</span>
                            <span class="text-2xl font-black text-white tracking-tight">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="block w-full rounded-[24px] bg-white py-5 text-center text-sm font-black text-slate-900 shadow-xl hover:bg-slate-50 transition-all active:scale-[0.98]">
                        Proceed to Checkout
                    </a>
                </div>
            </div>
        @endif
    </div>
@endsection
