@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 pb-24">
        <div class="flex items-center gap-3">
            <a href="{{ route('stores.pos.index', $store) }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight">Struk Transaksi</h1>
                <p class="text-slate-500 text-sm font-medium">{{ $store->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div
                class="rounded-2xl bg-green-50 border border-green-200 px-6 py-4 text-green-800 font-bold text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- Receipt Card --}}
        <div class="bg-white rounded-[32px] shadow-xl ring-1 ring-slate-100 overflow-hidden" id="receipt-card">
            {{-- Header --}}
            <div class="bg-slate-900 px-8 py-8 text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-white/10 mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-xl font-black text-white">{{ $store->name }}</h2>
                <p class="text-slate-400 text-xs font-medium mt-1">{{ $store->address }}</p>
                <div class="mt-4 inline-block bg-white/10 rounded-full px-4 py-1.5">
                    <span
                        class="text-xs font-black text-slate-300 font-mono tracking-widest">{{ $transaction->transaction_number }}</span>
                </div>
            </div>

            <div class="px-8 py-6 space-y-6">
                {{-- Transaction Info --}}
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal</p>
                        <p class="font-black text-slate-900">{{ $transaction->created_at->format('d M Y') }}</p>
                        <p class="text-slate-500 font-medium">{{ $transaction->created_at->format('H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Kasir</p>
                        <p class="font-black text-slate-900">{{ $transaction->cashier_name }}</p>
                        @if($transaction->customer_name)
                            <p class="text-slate-500 font-medium text-xs">Pelanggan: {{ $transaction->customer_name }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Pembayaran</p>
                        <p class="font-black text-slate-900">
                            {{ $transaction->payment_method_label }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Status</p>
                        <span
                            class="inline-flex rounded-full bg-green-50 px-3 py-1 text-xs font-black text-green-700">Lunas</span>
                    </div>
                </div>

                {{-- Items --}}
                <div class="border-t border-dashed border-slate-200 pt-5">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Item Produk</p>
                    <div class="space-y-3">
                        @foreach($transaction->items as $item)
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0 mr-4">
                                    <p class="font-black text-slate-900 text-sm truncate">{{ $item->product_name }}</p>
                                    <p class="text-xs text-slate-400 font-medium">{{ $item->quantity }} × Rp
                                        {{ number_format($item->unit_price, 0, ',', '.') }}</p>
                                </div>
                                <p class="font-black text-slate-900 text-sm flex-shrink-0">Rp
                                    {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Summary --}}
                <div class="border-t border-slate-100 pt-5 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500 font-medium">Subtotal</span>
                        <span class="font-black text-slate-700">Rp
                            {{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->discount > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Diskon</span>
                            <span class="font-black text-red-600">− Rp
                                {{ number_format($transaction->discount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base border-t border-slate-100 pt-3">
                        <span class="font-black text-slate-900">Total</span>
                        <span class="font-black text-indigo-700 text-xl">Rp
                            {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-sm py-1">
                        <span class="text-slate-500 font-medium">Dibayar</span>
                        <span class="font-bold text-slate-700">Rp
                            {{ number_format($transaction->amount_paid, 0, ',', '.') }}</span>
                    </div>
                    @if($transaction->payment_splits)
                        <div class="space-y-1 pl-4 border-l-2 border-slate-100 ml-1 mb-2">
                            @foreach($transaction->payment_splits as $split)
                                <div class="flex justify-between text-[11px] text-slate-400 font-bold italic">
                                    <span>{{ $split['label'] }} ({{ $split['account_name'] ?? '-' }})</span>
                                    <span>Rp {{ number_format($split['amount'], 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    @if($transaction->change_amount > 0)
                        <div class="flex justify-between text-sm bg-green-50 rounded-xl px-4 py-2.5">
                            <span class="text-green-700 font-black">Kembalian</span>
                            <span class="font-black text-green-700">Rp
                                {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                @if($transaction->notes)
                    <div class="bg-slate-50 rounded-2xl px-4 py-3">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Catatan</p>
                        <p class="text-sm text-slate-700 font-medium">{{ $transaction->notes }}</p>
                    </div>
                @endif

                <p class="text-center text-xs text-slate-400 font-medium">Terima kasih telah berbelanja di
                    {{ $store->name }}!</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <a href="{{ route('stores.pos.create', $store) }}"
                class="flex-1 text-center rounded-2xl border border-slate-200 py-3.5 text-sm font-black text-slate-600 hover:bg-slate-50 transition-all">
                Transaksi Baru
            </a>
            <button onclick="window.print()"
                class="flex-1 rounded-2xl bg-slate-900 py-3.5 text-sm font-black text-white hover:bg-indigo-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.056 48.056 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                </svg>
                Cetak Struk
            </button>
        </div>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #receipt-card,
            #receipt-card * {
                visibility: visible;
            }

            #receipt-card {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
            }
        }
    </style>
@endsection