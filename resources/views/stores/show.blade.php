@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-3">
                    <div
                        class="flex h-12 w-12 items-center justify-center rounded-2xl bg-indigo-600 text-white shadow-lg shadow-indigo-200">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H22.25m-12.917-14.746 3.917-3.917a1.125 1.125 0 0 1 1.591 0L19.083 6.254M1.75 10.5c.621 0 1.125-.504 1.125-1.125V3a.75.75 0 0 1 .75-.75h14.25a.75.75 0 0 1 .75.75v6.375c0 .621.504 1.125 1.125 1.125M1.75 10.5h20.5m-20.5 0v10.5h20.5V10.5" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold leading-7 text-slate-900 sm:truncate tracking-tight">
                            {{ $store->name }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500 flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $store->address ?? 'No address' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
                <a href="{{ route('stores.edit', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Edit Store
                </a>
                <a href="{{ route('stores.finance.transactions.index', $store) }}"
                    class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-95">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    New Transaction
                </a>
                <a href="{{ route('stores.products.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    Manage Products
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Financial Widget -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Financial Balance</h3>
                    <a href="{{ route('stores.finance.accounts.index', $store) }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">View Accounts &rarr;</a>
                </div>
                <div class="p-8 bg-slate-50/50 flex-1">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-900">Rp
                            {{ number_format($totalBalance, 2, ',', '.') }}</span>
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total</span>
                    </div>

                    <div class="mt-8 space-y-4">
                        @foreach($accounts as $account)
                            <a href="{{ route('stores.finance.accounts.show', [$store, $account]) }}"
                                class="flex items-center justify-between bg-white p-4 rounded-2xl shadow-sm ring-1 ring-slate-100 hover:ring-indigo-600 hover:shadow-md transition-all group">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-xs group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                                        {{ substr($account->name, 0, 2) }}
                                    </div>
                                    <span
                                        class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $account->name }}</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-black text-slate-900">Rp
                                        {{ number_format($account->balance, 2, ',', '.') }}</span>
                                    <span
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter group-hover:text-indigo-500 transition-colors">Lihat
                                        Mutasi &rarr;</span>
                                </div>
                            </a>
                        @endforeach
                        @if($accounts->isEmpty())
                            <p class="text-sm text-slate-500 italic text-center py-4">No accounts created yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div class="bg-white p-6 rounded-3xl shadow-sm ring-1 ring-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total SKUs</p>
                    <p class="mt-2 text-3xl font-bold text-slate-900">{{ $storeProducts->count() }}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm ring-1 ring-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Low Stock Items</p>
                    <p class="mt-2 text-3xl font-bold text-rose-600">{{ $storeProducts->where('stock', '<=', 5)->count() }}
                    </p>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm ring-1 ring-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Inter-Store Transfers</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <p class="text-3xl font-bold text-slate-900">Manage</p>
                        <a href="{{ route('stores.transfers.index', $store) }}"
                            class="text-xs font-semibold text-indigo-600 hover:underline">View & Transfer &rarr;</a>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm ring-1 ring-slate-100">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Deliveries</p>
                    <div class="mt-2 flex items-baseline gap-2">
                        <p class="text-3xl font-bold text-slate-900">{{ $recentTransfers->count() }}</p>
                        <a href="{{ route('stores.goods-requests.index', $store) }}"
                            class="text-xs font-semibold text-indigo-600 hover:underline">Goods Requests &rarr;</a>
                    </div>
                </div>
            </div>
        </div>

        {{-- POS & Trade-In Quick Access --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            {{-- POS --}}
            <a href="{{ route('stores.pos.create', $store) }}"
                class="group relative overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[28px] p-7 shadow-xl shadow-indigo-200 hover:shadow-2xl hover:shadow-indigo-300 hover:-translate-y-1 transition-all duration-300">
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')] opacity-10">
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-indigo-200 text-xs font-black uppercase tracking-widest group-hover:text-white transition-colors">Buka
                            →</span>
                    </div>
                    <p class="text-white/70 text-xs font-black uppercase tracking-widest mb-1">Point of Sale</p>
                    <h3 class="text-2xl font-black text-white leading-tight">Terminal Kasir</h3>
                    <p class="text-indigo-200 text-sm font-medium mt-2">Proses transaksi penjualan langsung di toko</p>
                </div>
            </a>

            {{-- Trade-In --}}
            <a href="{{ route('stores.trade-ins.index', $store) }}"
                class="group relative overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 rounded-[28px] p-7 shadow-xl shadow-slate-200 hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')] opacity-10">
                </div>
                <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/5 rounded-full"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </div>
                        <span
                            class="text-slate-400 text-xs font-black uppercase tracking-widest group-hover:text-white transition-colors">Lihat
                            →</span>
                    </div>
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1">Trade-In</p>
                    <h3 class="text-2xl font-black text-white leading-tight">Tukar Tambah</h3>
                    <p class="text-slate-400 text-sm font-medium mt-2">Terima & nilai perangkat bekas pelanggan</p>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Activity -->
            <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Recent Transactions</h3>
                    <a href="{{ route('stores.finance.transactions.index', $store) }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">View All</a>
                </div>
                <div class="p-8">
                    @if($recentTransactions->count() > 0)
                        <ul role="list" class="space-y-6">
                            @foreach($recentTransactions as $transaction)
                                <li class="flex items-center gap-x-4">
                                    <div
                                        class="flex-none rounded-full p-2 {{ $transaction->type === 'income' ? 'bg-green-50 text-green-600' : 'bg-rose-50 text-rose-600' }}">
                                        @if($transaction->type === 'income')
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 4.5v15m0 0l6.75-6.75M12 19.5l-6.75-6.75" />
                                            </svg>
                                        @else
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="flex-auto">
                                        <p class="text-sm font-bold text-slate-900">{{ $transaction->title }}</p>
                                        <p class="text-xs text-slate-500">{{ $transaction->transaction_date->format('d M Y') }} •
                                            {{ $transaction->account->name }}
                                        </p>
                                    </div>
                                    <div
                                        class="text-sm font-bold {{ $transaction->type === 'income' ? 'text-green-600' : 'text-rose-600' }}">
                                        {{ $transaction->type === 'income' ? '+' : '-' }}Rp
                                        {{ number_format($transaction->amount, 2, ',', '.') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-500 italic text-center py-4">No recent transactions.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Warehouse Transfers -->
            <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Incoming Deliveries</h3>
                    <a href="{{ route('inventory.warehouse-to-store.index') }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">View All</a>
                </div>
                <div class="p-8">
                    @if($recentTransfers->count() > 0)
                        <ul role="list" class="space-y-6">
                            @foreach($recentTransfers as $transfer)
                                <li class="flex items-center gap-x-4">
                                    <div class="flex-none rounded-xl bg-blue-50 p-2.5 text-blue-600">
                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V11.25" />
                                        </svg>
                                    </div>
                                    <div class="flex-auto">
                                        <p class="text-sm font-bold text-slate-900">{{ $transfer->transfer_number }}</p>
                                        <p class="text-xs text-slate-500">From {{ $transfer->fromWarehouse->name }} •
                                            {{ $transfer->transfer_date->format('d M Y') }}
                                        </p>
                                    </div>
                                    <a href="{{ route('inventory.warehouse-to-store.show', $transfer) }}"
                                        class="rounded-lg bg-slate-50 px-3 py-1 text-xs font-bold text-slate-600 hover:bg-slate-100">View</a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-slate-500 italic text-center py-4">No recent deliveries.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection