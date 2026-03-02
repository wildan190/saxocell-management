@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="px-4">
            <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900 uppercase">Financial Reports</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">Overview of the business's financial performance.</p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4">
            <div class="bg-white p-8 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-indigo-500">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Total Cash Assets</p>
                <h3 class="text-3xl font-black text-slate-900 tabular-nums">Rp
                    {{ number_format($totalBalance, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-8 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-emerald-500">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Monthly Income</p>
                <h3 class="text-3xl font-black text-slate-900 tabular-nums">Rp
                    {{ number_format($monthlyIncome, 0, ',', '.') }}</h3>
            </div>
            <div class="bg-white p-8 rounded-[32px] shadow-sm ring-1 ring-slate-100 border-b-4 border-rose-500">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Monthly Expenses</p>
                <h3 class="text-3xl font-black text-slate-900 tabular-nums">Rp
                    {{ number_format($monthlyExpense, 0, ',', '.') }}</h3>
            </div>
        </div>

        <!-- Report Links -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-4">
            <a href="{{ route('finance.reports.ledger') }}"
                class="group bg-slate-900 p-8 rounded-[32px] shadow-lg hover:bg-indigo-600 transition-all">
                <div
                    class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white uppercase tracking-tight">General Ledger</h3>
                <p class="text-slate-400 text-xs mt-2 font-medium group-hover:text-white/80 transition-colors">Detailed
                    transaction history across all accounts.</p>
            </a>

            <a href="{{ route('finance.reports.cashflow') }}"
                class="group bg-slate-900 p-8 rounded-[32px] shadow-lg hover:bg-emerald-600 transition-all">
                <div
                    class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white uppercase tracking-tight">Cashflow</h3>
                <p class="text-slate-400 text-xs mt-2 font-medium group-hover:text-white/80 transition-colors">Monthly
                    breakdown of inflows and outflows.</p>
            </a>

            <a href="{{ route('finance.reports.neraca') }}"
                class="group bg-slate-900 p-8 rounded-[32px] shadow-lg hover:bg-amber-600 transition-all">
                <div
                    class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white uppercase tracking-tight">Balance Sheet</h3>
                <p class="text-slate-400 text-xs mt-2 font-medium group-hover:text-white/80 transition-colors">Snapshot of
                    total assets and equity (Neraca).</p>
            </a>

            <a href="{{ route('finance.reports.pnl') }}"
                class="group bg-slate-900 p-8 rounded-[32px] shadow-lg hover:bg-rose-600 transition-all">
                <div
                    class="h-12 w-12 bg-white/10 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <h3 class="text-lg font-black text-white uppercase tracking-tight">Profit & Loss</h3>
                <p class="text-slate-400 text-xs mt-2 font-medium group-hover:text-white/80 transition-colors">Revenue vs
                    Expenses statement (Laba Rugi).</p>
            </a>
        </div>
    </div>
@endsection