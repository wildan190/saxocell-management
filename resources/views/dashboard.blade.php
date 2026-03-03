@extends('layouts.app')

@section('content')
    <div class="space-y-10 pb-12">
        <header class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black leading-tight tracking-tight text-slate-900">Dashboard Overview</h1>
                <p class="text-sm font-medium text-slate-500">Real-time statistics across all stores and warehouses.</p>
            </div>
            <div class="hidden sm:flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                </span>
                LIVE UPDATING
            </div>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Total Balance --}}
            <div
                class="relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm ring-1 ring-slate-100 group transition-all hover:shadow-xl hover:-translate-y-1">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500">
                </div>
                <dt class="relative z-10 truncate text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Total
                    Saldo (Global)</dt>
                <dd class="relative z-10 text-2xl font-black tracking-tight text-slate-900">
                    Rp {{ number_format($totalBalance, 0, ',', '.') }}
                </dd>
                <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-indigo-600">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path d="M5 10l7-7m0 0l7 7m-7-7v18" />
                    </svg>
                    Across {{ $totalWarehouses + $totalStores }} locations
                </div>
            </div>

            {{-- Today's POS --}}
            <div
                class="relative overflow-hidden rounded-[32px] bg-indigo-600 p-6 shadow-xl shadow-indigo-100 group transition-all hover:shadow-indigo-200 hover:-translate-y-1">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500">
                </div>
                <dt class="relative z-10 truncate text-xs font-black text-indigo-200 uppercase tracking-widest mb-1">
                    Penjualan Hari Ini</dt>
                <dd class="relative z-10 text-2xl font-black tracking-tight text-white">
                    Rp {{ number_format($todayPosRevenue, 0, ',', '.') }}
                </dd>
                <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-indigo-100">
                    {{ $todayPosCount }} Transaksi Terproses
                </div>
            </div>

            {{-- Pending Trade-Ins --}}
            <div
                class="relative overflow-hidden rounded-[32px] bg-slate-900 p-6 shadow-xl shadow-slate-200 group transition-all hover:shadow-slate-300 hover:-translate-y-1">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500">
                </div>
                <dt class="relative z-10 truncate text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Trade-In
                    Menunggu</dt>
                <dd class="relative z-10 text-2xl font-black tracking-tight text-white">
                    {{ $pendingTradeIns }}
                </dd>
                <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-amber-400">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L1 21h22L12 2zm0 3.99L19.53 19H4.47L12 5.99zM11 16h2v2h-2zm0-6h2v4h-2z" />
                    </svg>
                    Butuh Persetujuan Segera
                </div>
            </div>

            {{-- Inventory Reach --}}
            <div
                class="relative overflow-hidden rounded-[32px] bg-white p-6 shadow-sm ring-1 ring-slate-100 group transition-all hover:shadow-xl hover:-translate-y-1">
                <div
                    class="absolute -right-4 -top-4 w-24 h-24 bg-rose-50 rounded-full opacity-50 group-hover:scale-125 transition-transform duration-500">
                </div>
                <dt class="relative z-10 truncate text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Katalog
                    Produk</dt>
                <dd class="relative z-10 text-2xl font-black tracking-tight text-slate-900">
                    {{ $totalProducts }} SKUs
                </dd>
                <div class="mt-4 flex items-center gap-1 text-[10px] font-bold text-slate-500 uppercase">
                    Master Inventory Management
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent POS Transactions -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Transaksi POS Terakhir</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Across all stores</p>
                    </div>
                </div>
                <div class="p-8">
                    @if($recentPosTransactions->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-my-6 divide-y divide-slate-50">
                                @foreach($recentPosTransactions as $pos)
                                    <li class="py-6 flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="h-10 w-10 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 transition-colors">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                    stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.375c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125V6.375c0-.621-.504-1.125-1.125-1.125H3.375zM11.25 10.5h1.5v1.5h-1.5v-1.5zm0 3h1.5v1.5h-1.5v-1.5z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-slate-900">{{ $pos->transaction_number }}</p>
                                                <p class="text-xs text-slate-500 font-medium">{{ $pos->store->name }} •
                                                    {{ $pos->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-indigo-600">Rp
                                                {{ number_format($pos->total_amount, 0, ',', '.') }}</p>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $pos->payment_method }}</p>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <p class="text-sm font-medium text-slate-500 italic">Belum ada transaksi POS masuk hari ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Trade-Ins -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-900">Trade-In Terakhir</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Pending & Approved</p>
                    </div>
                </div>
                <div class="p-8">
                    @if($recentTradeIns->count() > 0)
                        <div class="flow-root">
                            <ul role="list" class="-my-6 divide-y divide-slate-50">
                                @foreach($recentTradeIns as $ti)
                                                    <li class="py-6 flex items-center justify-between">
                                                        <div class="flex items-center gap-4">
                                                            <div
                                                                class="h-10 w-10 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-600 transition-colors">
                                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                                                    stroke-width="2">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                        d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-black text-slate-900">{{ $ti->device_name }}</p>
                                                                <p class="text-xs text-slate-500 font-medium">{{ $ti->store->name }} •
                                                                    {{ $ti->customer_name }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="text-right">
                                                            @php
                                                                $statusColors = ['pending' => 'bg-amber-100 text-amber-700', 'approved' => 'bg-green-100 text-green-700', 'rejected' => 'bg-red-100 text-red-700'];
                                                            @endphp
                                     <span
                                                                class="inline-flex px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tight {{ $statusColors[$ti->status] ?? 'bg-slate-100 text-slate-600' }}">
                                                                {{ $ti->status }}
                                                            </span>
                                                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">
                                                                {{ $ti->created_at->format('H:i') }} WIB</p>
                                                        </div>
                                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @else
                        <div class="py-12 text-center">
                            <p class="text-sm font-medium text-slate-500 italic">Tidak ada pengajuan trade-in terbaru.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Stock Alerts -->
        <div class="bg-rose-50/50 rounded-[40px] px-8 py-10 ring-1 ring-rose-100">
            <h3 class="text-xl font-black text-rose-900 mb-8 flex items-center gap-3">
                <svg class="w-6 h-6 animate-pulse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                </svg>
                Peringatan Stok Rendah
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Store Low Stock --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-rose-100/50">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Stock Tipis di Toko</h4>
                    @forelse($lowStockStore as $ls)
                        <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ $ls->product->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                    {{ $ls->store->name }}</p>
                            </div>
                            <span class="text-sm font-black text-rose-600">{{ $ls->stock }} Unit</span>
                        </div>
                    @empty
                        <p class="text-sm font-medium text-slate-500 italic py-4">Stok toko masih aman.</p>
                    @endforelse
                </div>

                {{-- Warehouse Low Stock --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-rose-100/50">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Stock Tipis di Gudang</h4>
                    @forelse($lowStockWarehouse as $lw)
                        <div class="flex items-center justify-between py-3 border-b border-slate-50 last:border-0">
                            <div>
                                <p class="text-sm font-black text-slate-900">{{ $lw->product->name }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">
                                    {{ $lw->warehouse->name }}</p>
                            </div>
                            <span class="text-sm font-black text-rose-600">{{ $lw->quantity }} Unit</span>
                        </div>
                    @empty
                        <p class="text-sm font-medium text-slate-500 italic py-4">Stok gudang masih aman.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection