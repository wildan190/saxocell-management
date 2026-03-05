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
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900 uppercase">Profit & Loss (Laba
                    Rugi)</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium italic">Summary of revenue and expenses for the selected
                    period.</p>
            </div>

            <form action="{{ route('finance.reports.pnl') }}" method="GET" class="mt-4 sm:mt-0 flex gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">From</label>
                    <input type="date" name="start_date" value="{{ $startDate }}"
                        class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">To</label>
                    <input type="date" name="end_date" value="{{ $endDate }}"
                        class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm font-bold">
                </div>
                <button type="submit"
                    class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-slate-800 transition-all shadow-lg active:scale-95">Analyze</button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Expenses -->
            <div
                class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden border border-slate-50 flex flex-col">
                <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Operational Costs</h2>
                    <span class="text-rose-600 font-black tabular-nums">- Rp
                        {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                </div>
                <div class="flex-1 p-10">
                    <ul class="space-y-6">
                        @forelse($expenses as $exp)
                            <li class="flex justify-between items-center group">
                                <span
                                    class="text-sm font-black text-slate-600 uppercase tracking-widest group-hover:text-slate-900 transition-colors">{{ $exp->category }}</span>
                                <span class="text-sm font-bold tabular-nums text-slate-900 italic">Rp
                                    {{ number_format($exp->total, 0, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="py-10 text-center text-slate-400 italic">No expenses recorded in this period.</li>
                        @endforelse
                    </ul>
                </div>
                <div class="px-10 py-8 bg-rose-50/30 border-t border-rose-100 flex justify-between items-center italic">
                    <span class="text-xs font-black uppercase tracking-widest text-rose-700">Total Outgoings</span>
                    <span class="text-xl font-black text-rose-600 underline tabular-nums decoration-rose-200">Rp
                        {{ number_format($totalExpenses, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Revenue -->
            <div
                class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden border border-slate-50 flex flex-col">
                <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="text-lg font-black text-slate-900 uppercase tracking-tighter">Revenue</h2>
                    <span class="text-emerald-600 font-black tabular-nums">+ Rp
                        {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
                <div class="flex-1 p-10">
                    <ul class="space-y-6">
                        @forelse($revenue as $rev)
                            <li class="flex justify-between items-center group">
                                <span
                                    class="text-sm font-black text-slate-600 uppercase tracking-widest group-hover:text-slate-900 transition-colors">{{ $rev->category }}</span>
                                <span class="text-sm font-bold tabular-nums text-slate-900 italic">Rp
                                    {{ number_format($rev->total, 0, ',', '.') }}</span>
                            </li>
                        @empty
                            <li class="py-10 text-center text-slate-400 italic">No revenue objects in this period.</li>
                        @endforelse
                    </ul>
                </div>
                <div
                    class="px-10 py-8 bg-emerald-50/30 border-t border-emerald-100 flex justify-between items-center italic">
                    <span class="text-xs font-black uppercase tracking-widest text-emerald-700">Total Income</span>
                    <span class="text-xl font-black text-emerald-600 underline tabular-nums decoration-emerald-200">Rp
                        {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Net Result -->
        <div class="bg-slate-900 rounded-[40px] p-12 shadow-2xl overflow-hidden relative group">
            <div
                class="absolute inset-0 bg-gradient-to-r from-indigo-500/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700">
            </div>
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <h2 class="text-xs font-black uppercase tracking-[0.4em] text-indigo-400 mb-3">Profit & Loss Result</h2>
                    <p class="text-slate-400 text-sm font-medium italic">Net earnings after deducting all operational
                        expenses from total revenue.</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Net Profit/Loss</p>
                    <h3 class="text-5xl font-black tabular-nums {{ $netProfit >= 0 ? 'text-white' : 'text-rose-500' }}">
                        @if($netProfit < 0) - @endif Rp {{ number_format(abs($netProfit), 0, ',', '.') }}
                    </h3>
                    @if($netProfit >= 0)
                        <span
                            class="inline-flex items-center px-3 py-1 mt-4 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/20 text-emerald-400 ring-1 ring-emerald-500/50">
                            Profit Generated
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 mt-4 rounded-full text-[10px] font-black uppercase tracking-widest bg-rose-500/20 text-rose-400 ring-1 ring-rose-500/50">
                            Net Loss
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection