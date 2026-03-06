@extends('layouts.app')

@section('content')
    <div class="space-y-8 pb-24">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <a href="{{ route('stores.show', $store) }}"
                        class="text-slate-400 hover:text-slate-600 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Riwayat POS</h1>
                </div>
                <p class="text-slate-500 font-medium ml-8">{{ $store->name }}</p>
            </div>
            <a href="{{ route('stores.pos.create', $store) }}"
                class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-black text-white hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Buka Kasir
            </a>
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

        {{-- Transactions Table --}}
        <div class="bg-white rounded-[28px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            @if($transactions->isEmpty())
                <div class="py-24 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-slate-50 mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" />
                        </svg>
                    </div>
                    <p class="text-xl font-black text-slate-400">Belum ada transaksi POS</p>
                    <p class="text-slate-400 text-sm mt-1">Mulai transaksi pertama dengan klik "Buka Kasir"</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">No.
                                    Transaksi</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Kasir</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Produk</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Pembayaran</th>
                                <th class="text-right px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Total</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Trade-In</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Waktu</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($transactions as $tx)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <span
                                            class="font-black text-slate-900 font-mono text-xs">{{ $tx->transaction_number }}</span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">{{ $tx->cashier_name }}</td>
                                    <td class="px-6 py-4">
                                        <div class="max-w-[200px] truncate group relative">
                                            <span class="text-xs font-bold text-slate-600">
                                                {{ $tx->items->pluck('product_name')->join(', ') }}
                                            </span>
                                            {{-- Tooltip style detail for longer lists --}}
                                            @if($tx->items->count() > 2)
                                                <div
                                                    class="hidden group-hover:block absolute z-10 bg-slate-900 text-white p-2 rounded-lg text-[10px] w-48 shadow-xl">
                                                    {{ $tx->items->pluck('product_name')->join(', ') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @php
                                            $payBadge = ['cash' => 'bg-green-50 text-green-700', 'transfer' => 'bg-blue-50 text-blue-700', 'qris' => 'bg-purple-50 text-purple-700'];
                                            $payLabel = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'];
                                        @endphp
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase {{ $payBadge[$tx->payment_method] ?? 'bg-slate-100 text-slate-600' }}">
                                            {{ $payLabel[$tx->payment_method] ?? $tx->payment_method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-slate-900">Rp
                                        {{ number_format($tx->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-700">
                                        @if($tx->is_trade_in)
                                            <div class="flex flex-col">
                                                <span
                                                    class="inline-flex rounded-full bg-slate-900 px-2 py-0.5 text-[10px] font-black text-white uppercase mb-1 w-fit">Tukar
                                                    Tambah</span>
                                                <span
                                                    class="text-xs font-bold text-slate-600 truncate max-w-[120px]">{{ $tx->trade_in_device_name }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400">—</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 text-xs font-medium">
                                        <span
                                            title="{{ $tx->created_at->format('d M Y H:i') }}">{{ formatIndonesianRelativeTime($tx->created_at) }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('stores.pos.show', [$store, $tx]) }}"
                                            class="inline-flex items-center gap-1 text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors">
                                            Detail →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection