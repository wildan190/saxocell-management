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
            <div class="mt-4 flex flex-wrap md:ml-4 md:mt-0 gap-3">
                <a href="{{ route('stores.edit', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Edit Store
                </a>
                <a href="{{ route('stores.finance.transactions.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Transactions
                </a>
                <a href="{{ route('stores.goods-receipts.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Goods Receipt
                </a>
                <a href="{{ route('stores.opname.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-white border border-slate-200 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                    Stock Opname
                </a>
                <a href="{{ route('stores.products.index', $store) }}"
                    class="inline-flex items-center px-4 py-2.5 rounded-xl bg-indigo-600 text-sm font-bold text-white shadow-sm hover:bg-indigo-500 transition-all">
                    Stok Produk
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

        <!-- 1. Stats Grid (Highest Priority) -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
            {{-- Balance --}}
            <div class="bg-white p-7 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-indigo-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Saldo</p>
                <p class="text-2xl font-black text-slate-900 tracking-tight">Rp
                    {{ number_format($totalBalance, 0, ',', '.') }}
                </p>
                <a href="{{ route('stores.finance.accounts.index', $store) }}"
                    class="mt-4 inline-flex items-center text-[10px] font-bold text-indigo-600 uppercase hover:gap-1 transition-all">Detail
                    Akun &rarr;</a>
            </div>

            {{-- POS Revenue Today --}}
            <div class="bg-white p-7 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-emerald-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Penjualan Hari Ini</p>
                <p class="text-2xl font-black text-slate-900 tracking-tight">Rp
                    {{ number_format($todayPosRevenue, 0, ',', '.') }}
                </p>
                <p class="mt-4 text-[10px] font-bold text-slate-400 uppercase">Per {{ now()->format('d M Y') }}</p>
            </div>

            {{-- Stock Alerts --}}
            <div class="bg-white p-7 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-rose-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stok Menipis</p>
                <p class="text-2xl font-black text-rose-600">{{ $storeProducts->where('stock', '<=', 5)->count() }} <span
                        class="text-sm font-black text-slate-400 uppercase ml-1">Produk</span></p>
                <a href="{{ route('stores.products.index', $store) }}"
                    class="mt-4 inline-flex items-center text-[10px] font-bold text-rose-500 uppercase hover:gap-1 transition-all">Cek
                    Persediaan &rarr;</a>
            </div>

            {{-- Goods Receipt Count --}}
            <div class="bg-white p-7 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-amber-500">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Barang Masuk (Baru)</p>
                <p class="text-2xl font-black text-slate-900">{{ $recentGoodsReceipts->count() }} <span
                        class="text-sm font-black text-slate-400 uppercase ml-1">Nota</span></p>
                <a href="{{ route('stores.goods-receipts.index', $store) }}"
                    class="mt-4 inline-flex items-center text-[10px] font-bold text-slate-500 uppercase hover:gap-1 transition-all">Lihat
                    Riwayat &rarr;</a>
            </div>
        </div>

        <!-- 2. Primary Actions Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 mt-12">
            {{-- POS Quick Access --}}
            <a href="{{ route('stores.pos.create', $store) }}"
                class="group relative overflow-hidden bg-slate-900 rounded-[32px] p-8 shadow-2xl hover:-translate-y-1 transition-all duration-300">
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-transparent opacity-50"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
                            </svg>
                        </div>
                        <span
                            class="text-white/50 text-xs font-black uppercase tracking-widest group-hover:text-white transition-colors">Point
                            of Sale →</span>
                    </div>
                    <h3 class="text-3xl font-black text-white tracking-tight">Terminal Kasir</h3>
                    <p class="text-white/60 text-sm font-medium mt-2">Mulai transaksi penjualan baru</p>
                </div>
            </a>

            {{-- Goods Receipt Quick Access --}}
            <a href="{{ route('stores.goods-receipts.create', $store) }}"
                class="group relative overflow-hidden bg-white rounded-[32px] p-8 shadow-xl shadow-slate-100 ring-1 ring-slate-100 hover:-translate-y-1 transition-all duration-300">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-10">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M20.25 7.5l-.625 10.125a2.25 2.25 0 01-2.247 2.125H6.622a2.25 2.25 0 01-2.247-2.125L3.75 7.5m16.5 0c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H3.75c-.621 0-1.125.504-1.125 1.125v.75c0 .621.504 1.125 1.125 1.125m16.5 0a48.108 48.108 0 00-3.478-.397m-12.044.397a48.108 48.108 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                        </div>
                        <span class="text-indigo-600 text-xs font-black uppercase tracking-widest transition-all">Inventory
                            →</span>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 tracking-tight">Goods Receipt</h3>
                    <p class="text-slate-500 text-sm font-medium mt-2">Tambah stok masuk ke toko</p>
                </div>
            </a>
        </div>

        <!-- 3. Dual-Column Operational Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                {{-- LEFT COLUMN: Transaction Flow --}}
                <div class="space-y-8">
                    <!-- Recent POS Transactions -->
                    <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Transaksi POS Terkini</h3>
                            <a href="{{ route('stores.pos.index', $store) }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Semua &rarr;</a>
                        </div>
                        <div class="p-8 space-y-2">
                            @forelse($recentPosTransactions as $pos)
                                <div class="flex items-center justify-between py-4 group transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-50 group-hover:text-indigo-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 leading-none mb-1 group-hover:text-indigo-600 transition-colors">{{ $pos->transaction_number }}</p>
                                            <p class="text-[11px] text-slate-400 font-bold uppercase">{{ $pos->created_at->format('d M — H:i') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-black text-slate-900">Rp {{ number_format($pos->total_amount, 0, ',', '.') }}</p>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $pos->payment_method }}</p>
                                    </div>
                                </div>
                                @if(!$loop->last) <div class="border-b border-slate-50 mx-2"></div> @endif
                            @empty
                                <p class="text-sm text-slate-400 italic text-center py-4">Belum ada transaksi POS.</p>
                            @endforelse
                        </div>
                    </div>

                    <!-- Recent Goods Receipts -->
                    <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Penerimaan Barang Terkini</h3>
                            <a href="{{ route('stores.goods-receipts.index', $store) }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Semua &rarr;</a>
                        </div>
                        <div class="p-8 space-y-2">
                            @forelse($recentGoodsReceipts as $gr)
                                <div class="flex items-center justify-between py-4 group transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 leading-none mb-1">{{ $gr->receipt_number }}</p>
                                            <p class="text-[11px] text-slate-400 font-bold uppercase">{{ $gr->created_at->format('d M Y') }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="inline-flex rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-tight {{ $gr->sale_status === 'Terjual' ? 'bg-indigo-100 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $gr->sale_status }}
                                        </span>
                                    </div>
                                </div>
                                @if(!$loop->last) <div class="border-b border-slate-50 mx-2"></div> @endif
                            @empty
                                <p class="text-sm text-slate-400 italic text-center py-4">Belum ada barang masuk.</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Operational Context --}}
                <div class="space-y-8">
                    <!-- Finance Accounts Quick Glance -->
                    <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Saldo Akun Keuangan</h3>
                            <a href="{{ route('stores.finance.accounts.index', $store) }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Detail &rarr;</a>
                        </div>
                        <div class="p-8 space-y-4">
                            @foreach($accounts as $account)
                                <a href="{{ route('stores.finance.accounts.show', [$store, $account]) }}"
                                    class="flex items-center justify-between p-5 rounded-3xl bg-slate-50 border border-slate-100 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all group/acc">
                                    <div class="flex items-center gap-4">
                                        <div class="h-11 w-11 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-md shadow-indigo-100 group-hover/acc:scale-110 transition-transform">
                                            {{ strtoupper(substr($account->name, 0, 2)) }}
                                        </div>
                                        <span class="text-sm font-black text-slate-800 group-hover/acc:text-indigo-600 transition-colors">{{ $account->name }}</span>
                                    </div>
                                    <span class="text-base font-black text-slate-900">Rp {{ number_format($account->balance, 0, ',', '.') }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Warehouse Transfers -->
                    <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                        <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Pengiriman dari Gudang</h3>
                            <a href="{{ route('stores.goods-requests.index', $store) }}" class="text-[10px] font-black text-indigo-600 hover:underline uppercase tracking-widest">Lihat Semua &rarr;</a>
                        </div>
                        <div class="p-8 space-y-2">
                            @forelse($recentTransfers as $transfer)
                                <div class="flex items-center justify-between py-4 group transition-all">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-orange-50 flex items-center justify-center text-orange-600">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                    d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V11.25" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900 leading-none mb-1 group-hover:text-orange-600 transition-colors">{{ $transfer->transfer_number }}</p>
                                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-tight">Dari {{ $transfer->fromWarehouse->name }}</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('inventory.warehouse-to-store.show', $transfer) }}" class="px-4 py-2 rounded-xl bg-slate-50 text-[10px] font-bold uppercase text-slate-600 hover:bg-indigo-600 hover:text-white transition-all shadow-sm">Detail</a>
                                </div>
                                @if(!$loop->last) <div class="border-b border-slate-50 mx-2"></div> @endif
                            @empty
                                <p class="text-sm text-slate-400 italic text-center py-4">Belum ada pengiriman barang.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. Full-Width Audit Section -->
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden mt-12">
                <div class="px-8 py-7 border-b border-slate-50 flex items-center justify-between bg-slate-900 text-white">
                    <div>
                        <h3 class="text-xl font-black tracking-tight">Riwayat Transaksi Keuangan</h3>
                        <p class="text-white/40 text-[10px] font-bold uppercase tracking-widest mt-0.5">Audit log keuangan toko</p>
                    </div>
                    <a href="{{ route('stores.finance.transactions.index', $store) }}"
                        class="px-5 py-2.5 rounded-xl bg-white/10 text-[10px] font-black uppercase tracking-widest border border-white/10 hover:bg-white/20 transition-all">Lihat Laporan &rarr;</a>
                </div>
                <div class="p-8">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    <th class="pb-6 pl-4">Waktu</th>
                                    <th class="pb-6">Akun</th>
                                    <th class="pb-6">Kategori</th>
                                    <th class="pb-6">Tipe</th>
                                    <th class="pb-6 text-right">Jumlah</th>
                                    <th class="pb-6 pl-8">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($recentTransactions as $tx)
                                    <tr class="group hover:bg-slate-50/50 transition-colors">
                                        <td class="py-5 pl-4">
                                            <p class="text-sm font-black text-slate-900 leading-none mb-1">{{ $tx->created_at->format('d M') }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $tx->created_at->format('H:i') }}</p>
                                        </td>
                                        <td class="py-5">
                                            <div class="flex items-center gap-2">
                                                <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                                                <span class="text-sm font-black text-slate-800 tracking-tight">{{ $tx->account->name }}</span>
                                            </div>
                                        </td>
                                        <td class="py-5 font-bold text-xs text-slate-500 uppercase tracking-wide">{{ $tx->category }}</td>
                                        <td class="py-5">
                                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase {{ $tx->type === 'income' ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                                {{ $tx->type }}
                                            </span>
                                        </td>
                                        <td class="py-5 text-right">
                                            <span class="text-sm font-black tracking-tight {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                                {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td class="py-5 pl-8">
                                            <p class="text-xs font-semibold text-slate-500 max-w-sm truncate">{{ $tx->notes ?: '—' }}</p>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-slate-400 italic">Belum ada aktivitas keuangan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
@endsection