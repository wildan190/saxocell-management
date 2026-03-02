@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 pb-24 px-4">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('finance.reports.index') }}"
                    class="text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </a>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900 uppercase">Balance Sheet (Neraca)
                </h1>
                <p
                    class="mt-2 text-sm text-slate-500 font-medium italic underline decoration-slate-200 decoration-4 underline-offset-4">
                    Statement of financial position as of today.</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Assets Section -->
            <div class="space-y-6">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-widest flex items-center gap-4">
                    <span
                        class="h-8 w-8 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center text-xs">A</span>
                    Current Assets
                </h2>

                <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden border border-slate-50">
                    <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Warehouse Accounts</h3>
                    </div>
                    <ul class="divide-y divide-slate-50 px-8">
                        @foreach($warehouseAccounts as $acc)
                            <li class="py-4 flex justify-between items-center group">
                                <div>
                                    <p class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $acc->name }}</p>
                                    <p class="text-[10px] uppercase font-bold text-slate-400">{{ $acc->warehouse->name }}</p>
                                </div>
                                <span class="text-sm font-black tabular-nums text-slate-900">Rp
                                    {{ number_format($acc->balance, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="px-8 py-6 bg-slate-50/50 border-y border-slate-100">
                        <h3 class="text-xs font-black uppercase tracking-widest text-slate-400">Store Accounts</h3>
                    </div>
                    <ul class="divide-y divide-slate-50 px-8">
                        @foreach($storeAccounts as $acc)
                            <li class="py-4 flex justify-between items-center group">
                                <div>
                                    <p class="text-sm font-black text-slate-900 group-hover:text-indigo-600 transition-colors">
                                        {{ $acc->name }}</p>
                                    <p class="text-[10px] uppercase font-bold text-slate-400">{{ $acc->store->name }}</p>
                                </div>
                                <span class="text-sm font-black tabular-nums text-slate-900">Rp
                                    {{ number_format($acc->balance, 0, ',', '.') }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Summary & Equity -->
            <div class="space-y-6">
                <h2 class="text-xl font-black text-slate-900 uppercase tracking-widest flex items-center gap-4">
                    <span
                        class="h-8 w-8 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center text-xs">S</span>
                    Summary
                </h2>

                <div class="bg-slate-900 rounded-[40px] p-10 shadow-2xl space-y-8 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-8 opacity-10">
                        <svg class="h-32 w-32 text-indigo-400" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-400 mb-2">Total Estimated Assets
                        </p>
                        <h3 class="text-5xl font-black text-white tabular-nums">Rp
                            {{ number_format($totalAssets, 0, ',', '.') }}</h3>
                    </div>
                    <div class="pt-8 border-t border-white/10">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4">Account Type
                            Distribution</p>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center italic">
                                <span class="text-sm text-slate-300 font-bold">Total Accounts</span>
                                <span
                                    class="text-sm text-white font-black">{{ $warehouseAccounts->count() + $storeAccounts->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center italic">
                                <span class="text-sm text-slate-300 font-bold">Total Cash Balance</span>
                                <span class="text-sm text-indigo-400 font-black">Rp
                                    {{ number_format($totalAssets, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-[40px] p-8 ring-1 ring-slate-100 italic">
                    <p class="text-xs text-slate-400 font-medium">Note: The balance sheet currently represents liquid cash
                        assets from all registered financial accounts. Fixed assets like inventory stock value are tracked
                        separately in the inventory module.</p>
                </div>
            </div>
        </div>
    </div>
@endsection