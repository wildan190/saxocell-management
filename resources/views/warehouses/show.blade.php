@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Area -->
        <div
            class="lg:flex lg:items-center lg:justify-between p-8 bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 border-l-4 border-indigo-600">
            <div class="min-w-0 flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('warehouses.index') }}" class="hover:text-slate-700">Warehouses</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">{{ $warehouse->name }}</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black leading-7 text-slate-900 sm:truncate sm:text-4xl sm:tracking-tight">
                    {{ $warehouse->name }}
                </h2>
                <div class="mt-2 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
                    <div class="mt-2 flex items-center text-sm text-slate-500">
                        <svg class="mr-1.5 h-5 w-5 flex-shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z"
                                clip-rule="evenodd" />
                        </svg>
                        {{ $warehouse->location }}
                    </div>
                </div>
            </div>
            <div class="mt-5 flex lg:ml-4 lg:mt-0 gap-3">
                <a href="{{ route('inventory.goods-receipts.create', $warehouse) }}"
                    class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-95">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                    </svg>
                    Goods Receipt
                </a>
                <a href="{{ route('finance.transactions.index', $warehouse) }}"
                    class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-95">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    New Transaction
                </a>
                <a href="{{ route('inventory.opname.create', ['warehouse_id' => $warehouse->id]) }}"
                    class="inline-flex items-center rounded-xl bg-amber-500 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-400 transition-all active:scale-95">
                    <svg class="-ml-0.5 mr-1.5 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    Stock Opname
                </a>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Financial Widget -->
            <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Financial Balance</h3>
                    <a href="{{ route('finance.accounts.index', $warehouse) }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">View Accounts &rarr;</a>
                </div>
                <div class="p-8 bg-slate-50/50 flex-1">
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-black text-slate-900">Rp
                            {{ number_format($totalBalance, 0, ',', '.') }}</span>
                        <span class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total</span>
                    </div>

                    <div class="mt-8 space-y-4">
                        @foreach($accounts as $account)
                            <a href="{{ route('finance.accounts.show', [$warehouse, $account]) }}"
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
                    </div>
                </div>
            </div>

            <!-- Inventory Summary Widget -->
            <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden flex flex-col">
                <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-slate-900">Stock Availability</h3>
                    <a href="{{ route('inventory.overview') }}"
                        class="text-sm font-semibold text-indigo-600 hover:text-indigo-500">Full Overview &rarr;</a>
                </div>
                <div class="p-0 flex-1">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th class="py-3 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Product</th>
                                <th class="py-3 px-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                    Qty</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($inventories as $inv)
                                <tr class="hover:bg-slate-50/30">
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-800">{{ $inv->product->name }}</span>
                                            <span
                                                class="text-[10px] font-mono text-slate-400 uppercase">{{ $inv->product->sku }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <span
                                            class="text-sm font-black text-indigo-600">{{ number_format($inv->quantity, 0, ',', '.') }}</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $inv->product->unit }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-8 text-center text-sm text-slate-400 italic">No stock in this
                                        warehouse.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Activity History -->
        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Activity & Fulfillment</h3>
                <a href="{{ route('warehouses.incoming-requests.index', $warehouse) }}"
                    class="text-xs font-semibold text-indigo-600 hover:text-indigo-500">Manage Store Requests &rarr;</a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-y lg:divide-y-0 lg:divide-x divide-slate-100">
                <!-- Finance Activities -->
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="h-2 w-2 rounded-full bg-green-500"></span> Recent Transactions
                        </h4>
                        <div class="flex gap-2">
                            <a href="{{ route('finance.transactions.index', ['warehouse' => $warehouse->id, 'modal' => 'income']) }}"
                                class="text-[10px] font-bold text-green-600 hover:text-green-700 bg-green-50 px-2 py-0.5 rounded border border-green-100 transition-colors">
                                + Income
                            </a>
                            <a href="{{ route('finance.transactions.index', ['warehouse' => $warehouse->id, 'modal' => 'expense']) }}"
                                class="text-[10px] font-bold text-red-600 hover:text-red-700 bg-red-50 px-2 py-0.5 rounded border border-red-100 transition-colors">
                                + Expense
                            </a>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentTransactions as $tx)
                            <div class="flex flex-col gap-1 p-3 rounded-xl bg-slate-50/50">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-900 truncate pr-2">{{ $tx->title }}</span>
                                    <span
                                        class="text-[10px] font-black {{ $tx->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $tx->type === 'income' ? '+' : '-' }}Rp {{ number_format($tx->amount, 0, ',', '.') }}
                                    </span>
                                </div>
                                <span class="text-[10px] text-slate-500">{{ $tx->created_at->format('M d, H:i') }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No recent transactions.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Inventory Activities -->
                <div class="p-6 space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-indigo-500"></span> Recent Receipts
                    </h4>
                    <div class="space-y-3">
                        @forelse($recentReceipts as $receipt)
                            <a href="{{ route('inventory.goods-receipts.show', [$warehouse, $receipt]) }}"
                                class="block p-3 rounded-xl bg-slate-50/50 hover:bg-slate-100 transition-colors">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-900">{{ $receipt->receipt_number }}</span>
                                    <span class="text-[10px] font-bold text-slate-500">{{ $receipt->received_date }}</span>
                                </div>
                                <div class="mt-1 flex gap-1 flex-wrap">
                                    @foreach($receipt->items->take(2) as $item)
                                        <span
                                            class="text-[9px] bg-white px-1.5 py-0.5 rounded border border-slate-100 text-slate-600">{{ $item->product->name }}
                                            ({{ $item->quantity }})</span>
                                    @endforeach
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 italic">No recent receipts.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Transfer Activities -->
                <div class="p-6 space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-amber-500"></span> Recent Transfers
                    </h4>
                    <div class="space-y-3">
                        @forelse($recentTransfers as $transfer)
                            <div class="p-3 rounded-xl bg-slate-50/50">
                                <div class="flex justify-between items-center mb-1">
                                    <span
                                        class="text-xs font-bold text-slate-900 tracking-tight">{{ $transfer->transfer_number }}</span>
                                    <span class="text-[10px] font-bold text-slate-500">{{ $transfer->transfer_date }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="text-[9px] font-bold {{ $transfer->from_warehouse_id == $warehouse->id ? 'text-red-600 bg-red-50' : 'text-slate-500 bg-white' }} px-2 py-0.5 rounded border border-slate-100">
                                        {{ $transfer->fromWarehouse->name }}
                                    </span>
                                    <svg class="h-3 w-3 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                    <span
                                        class="text-[9px] font-bold {{ $transfer->to_warehouse_id == $warehouse->id ? 'text-green-600 bg-green-50' : 'text-slate-500 bg-white' }} px-2 py-0.5 rounded border border-slate-100">
                                        {{ $transfer->toWarehouse->name }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No recent transfers.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Store Requests -->
                <div class="p-6 space-y-4">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span> Incoming Requests
                    </h4>
                    <div class="space-y-3">
                        @forelse($recentStoreRequests as $storeReq)
                            <a href="{{ route('warehouses.incoming-requests.show', [$warehouse, $storeReq]) }}"
                                class="block p-3 rounded-xl bg-slate-50/50 hover:bg-slate-100 transition-colors">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-900">{{ $storeReq->request_number }}</span>
                                    <span
                                        class="text-[10px] font-bold text-slate-500">{{ $storeReq->created_at->format('M d') }}</span>
                                </div>
                                <div class="mt-1 flex items-center justify-between">
                                    <span
                                        class="text-[10px] font-medium text-slate-500 italic">{{ $storeReq->store->name }}</span>
                                    <span
                                        class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-white border border-slate-100
                                                                                {{ $storeReq->status === 'pending' ? 'text-amber-600' : '' }}
                                                                                {{ $storeReq->status === 'confirmed' ? 'text-blue-600' : '' }}
                                                                                {{ $storeReq->status === 'shipped' ? 'text-indigo-600' : '' }}
                                                                                {{ $storeReq->status === 'received' ? 'text-green-600' : '' }}">
                                        {{ $storeReq->status }}
                                    </span>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 italic">No incoming requests.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Stock Opname History -->
        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="p-6 border-t border-slate-100 space-y-4">
                <div class="flex items-center justify-between">
                    <h4 class="text-xs font-black text-slate-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="h-2 w-2 rounded-full bg-slate-900"></span> Recent Stock Opnames
                    </h4>
                    <a href="{{ route('inventory.opname.index', ['warehouse_id' => $warehouse->id]) }}"
                        class="text-[10px] font-bold text-indigo-600 hover:text-indigo-500 lowercase">View all
                        &rarr;</a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @forelse(\App\Models\StockOpname::where('warehouse_id', $warehouse->id)->latest()->take(4)->get() as $opname)
                        <a href="{{ route('inventory.opname.show', $opname) }}"
                            class="p-4 rounded-2xl bg-slate-50/50 border border-slate-100 hover:bg-slate-100 transition-all group">
                            <div class="flex justify-between items-start mb-2">
                                <span class="text-xs font-bold text-slate-900">{{ $opname->reference_number }}</span>
                                <span
                                    class="text-[9px] font-black uppercase px-1.5 py-0.5 rounded bg-white border border-slate-200 
                                                            {{ $opname->status === 'completed' ? 'text-green-600' : 'text-amber-600' }}">
                                    {{ $opname->status }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-slate-500">{{ $opname->created_at->format('M d, Y') }}</span>
                                <span class="text-slate-400 group-hover:text-indigo-600 transition-colors">Details
                                    &rarr;</span>
                            </div>
                        </a>
                    @empty
                        <div
                            class="col-span-full py-6 text-center text-xs text-slate-400 italic bg-slate-50/30 rounded-2xl border border-dashed border-slate-200">
                            No stock opname history for this warehouse.
                        </div>
                    @endforelse
                </div>
            </div>
@endsection