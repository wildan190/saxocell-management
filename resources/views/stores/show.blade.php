@extends('layouts.app')

@section('content')
    <div class="space-y-8 pb-12">
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
                        <h2 class="text-3xl font-black leading-7 text-slate-900 sm:truncate tracking-tight">
                            {{ $store->name }}
                        </h2>
                        <p class="mt-1 text-sm text-slate-500 flex items-center gap-1.5 font-medium">
                            <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Edit Store
                </a>
                <a href="{{ route('stores.finance.transactions.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Transactions
                </a>
                <a href="{{ route('stores.products.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 text-sm font-bold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    Manage Stock
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-2xl bg-green-50 p-4 border border-green-100 flex items-center gap-3">
                <svg class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                        clip-rule="evenodd" />
                </svg>
                <p class="text-sm font-bold text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Quick Stats Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Balance --}}
            <div class="bg-white p-6 rounded-[32px] shadow-sm ring-1 ring-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Saldo</p>
                <p class="text-2xl font-black text-slate-900">Rp {{ number_format($totalBalance, 0, ',', '.') }}</p>
                <a href="{{ route('stores.finance.accounts.index', $store) }}"
                    class="mt-4 block text-[10px] font-bold text-indigo-600 uppercase hover:underline">Detail Akun
                    &rarr;</a>
            </div>

            {{-- POS Revenue Today --}}
            <div class="bg-indigo-600 p-6 rounded-[32px] shadow-xl shadow-indigo-100">
                <p class="text-[10px] font-black text-indigo-200 uppercase tracking-widest mb-1">Penjualan Hari Ini</p>
                <p class="text-2xl font-black text-white">Rp {{ number_format($todayPosRevenue, 0, ',', '.') }}</p>
                <p class="mt-4 text-[10px] font-bold text-indigo-100 uppercase">Terminal POS Toko</p>
            </div>

            {{-- Pending Trade-Ins --}}
            <div class="bg-white p-6 rounded-[32px] shadow-sm ring-1 ring-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Trade-In Menunggu</p>
                <p class="text-2xl font-black text-slate-900">{{ $pendingTradeInsCount }}</p>
                <a href="{{ route('stores.trade-ins.index', $store) }}"
                    class="mt-4 block text-[10px] font-bold text-slate-500 uppercase hover:underline">Proses Sekarang
                    &rarr;</a>
            </div>

            {{-- Stock Alerts --}}
            <div class="bg-white p-6 rounded-[32px] shadow-sm ring-1 ring-slate-100">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Menipis</p>
                <p class="text-2xl font-black text-rose-600">{{ $storeProducts->where('stock', '<=', 5)->count() }}</p>
                <a href="{{ route('stores.products.index', $store) }}"
                    class="mt-4 block text-[10px] font-bold text-rose-500 uppercase hover:underline">Cek Persediaan
                    &rarr;</a>
            </div>
        </div>

        {{-- POS & Trade-In Quick Access --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            {{-- POS --}}
            <a href="{{ route('stores.pos.create', $store) }}"
                class="group relative overflow-hidden bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-[32px] p-8 shadow-xl shadow-indigo-100 hover:shadow-indigo-200 hover:-translate-y-1 transition-all duration-300">
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')] opacity-10">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-white/15 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-indigo-200 text-xs font-black uppercase tracking-widest group-hover:text-white transition-colors">Buka
                            Kasir →</span>
                    </div>
                    <p class="text-white/70 text-xs font-black uppercase tracking-widest mb-1 leading-none">Point of Sale
                    </p>
                    <h3 class="text-2xl font-black text-white">Terminal POS</h3>
                </div>
            </a>

            {{-- Trade-In --}}
            <a href="{{ route('stores.trade-ins.index', $store) }}"
                class="group relative overflow-hidden bg-gradient-to-br from-slate-800 to-slate-900 rounded-[32px] p-8 shadow-xl shadow-slate-200 hover:shadow-slate-300 hover:-translate-y-1 transition-all duration-300">
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')] opacity-10">
                </div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                            </svg>
                        </div>
                        <span
                            class="text-slate-400 text-xs font-black uppercase tracking-widest group-hover:text-white transition-colors">Lihat
                            Daftar →</span>
                    </div>
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1 leading-none">Trade-In</p>
                    <h3 class="text-2xl font-black text-white">Tukar Tambah</h3>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent POS Transactions -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-900">Transaksi POS Terkini</h3>
                    <a href="{{ route('stores.pos.index', $store) }}"
                        class="text-xs font-bold text-indigo-600 hover:underline uppercase">Lihat Semua</a>
                </div>
                <div class="p-8">
                    @forelse($recentPosTransactions as $pos)
                        <div class="flex items-center justify-between py-4 border-b border-slate-50 last:border-0">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $pos->transaction_number }}</p>
                                    <p class="text-xs text-slate-500 font-medium">{{ $pos->created_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-black text-indigo-600">Rp
                                    {{ number_format($pos->total_amount, 0, ',', '.') }}</p>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">{{ $pos->payment_method }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 italic text-center py-4">Belum ada transaksi POS.</p>
                    @endforelse
                </div>
            </div>

            <!-- Recent Trade-Ins -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-900">Trade-In Terkini</h3>
                    <a href="{{ route('stores.trade-ins.index', $store) }}"
                        class="text-xs font-bold text-indigo-600 hover:underline uppercase">Lihat Semua</a>
                </div>
                <div class="p-8">
                    @forelse($recentTradeIns as $ti)
                        <div class="flex items-center justify-between py-4 border-b border-slate-50 last:border-0">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $ti->device_name }}</p>
                                    <p class="text-xs text-slate-500 font-medium">{{ $ti->customer_name }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase tracking-tight
                                            {{ $ti->status === 'pending' ? 'bg-amber-100 text-amber-700' : ($ti->status === 'approved' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') }}">
                                    {{ $ti->status }}
                                </span>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase">
                                    {{ $ti->created_at->format('d M') }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 italic text-center py-4">Belum ada pengajuan trade-in.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Finance Accounts Detail -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-900">Saldo Akun Keuangan</h3>
                    <a href="{{ route('stores.finance.accounts.index', $store) }}"
                        class="text-xs font-bold text-indigo-600 hover:underline uppercase">Edit Akun</a>
                </div>
                <div class="p-8 space-y-4">
                    @foreach($accounts as $account)
                        <div
                            class="flex items-center justify-between p-4 rounded-2xl bg-slate-50/50 ring-1 ring-slate-100 hover:shadow-md transition-all">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-xs">
                                    {{ strtoupper(substr($account->name, 0, 2)) }}
                                </div>
                                <span class="text-sm font-black text-slate-700">{{ $account->name }}</span>
                            </div>
                            <span class="text-sm font-black text-slate-900">Rp
                                {{ number_format($account->balance, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Recent Requests/Transfers -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-black text-slate-900">Pengiriman Barang (Gudang)</h3>
                    <a href="{{ route('stores.goods-requests.index', $store) }}"
                        class="text-xs font-bold text-indigo-600 hover:underline uppercase">Semua Permintaan</a>
                </div>
                <div class="p-8">
                    @forelse($recentTransfers as $transfer)
                        <div class="flex items-center justify-between py-4 border-b border-slate-50 last:border-0">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V11.25" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $transfer->transfer_number }}</p>
                                    <p class="text-xs text-slate-500 font-medium">Dari {{ $transfer->fromWarehouse->name }}</p>
                                </div>
                            </div>
                            <a href="{{ route('inventory.warehouse-to-store.show', $transfer) }}"
                                class="px-3 py-1 rounded-lg bg-slate-100 text-[10px] font-black uppercase text-slate-600 hover:bg-slate-200">Lihat</a>
                        </div>
                    @empty
                        <p class="text-sm text-slate-500 italic text-center py-4">Belum ada pengiriman barang.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Finance Transactions -->
        <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden mt-8">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-black text-slate-900">Riwayat Transaksi Keuangan</h3>
                <a href="{{ route('stores.finance.transactions.index', $store) }}"
                    class="text-xs font-bold text-indigo-600 hover:underline uppercase">Lihat Semua</a>
            </div>
            <div class="p-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-50">
                                <th class="pb-4">Tanggal</th>
                                <th class="pb-4">Akun</th>
                                <th class="pb-4">Tipe</th>
                                <th class="pb-4">Kategori</th>
                                <th class="pb-4">Jumlah</th>
                                <th class="pb-4">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recentTransactions as $tx)
                                <tr class="group hover:bg-slate-50 transition-colors">
                                    <td class="py-4 text-sm font-medium text-slate-600">
                                        {{ $tx->created_at->format('d M, H:i') }}</td>
                                    <td class="py-4">
                                        <span class="text-sm font-black text-slate-900">{{ $tx->account->name }}</span>
                                    </td>
                                    <td class="py-4">
                                        <span
                                            class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase
                                                    {{ $tx->type === 'income' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            {{ $tx->type }}
                                        </span>
                                    </td>
                                    <td class="py-4 font-black text-sm text-slate-700">{{ $tx->category }}</td>
                                    <td class="py-4">
                                        <span
                                            class="text-sm font-black {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $tx->type === 'income' ? '+' : '-' }} Rp
                                            {{ number_format($tx->amount, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-4 text-xs font-medium text-slate-500 max-w-xs truncate">{{ $tx->notes }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-slate-400 italic text-sm">Belum ada transaksi
                                        keuangan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection