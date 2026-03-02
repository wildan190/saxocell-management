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
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900 uppercase">Cashflow Statement
                </h1>
                <p class="mt-2 text-sm text-slate-500 font-medium italic">Monthly inflow and outflow analysis for
                    {{ $year }}.
                </p>
            </div>

            <form action="{{ route('finance.reports.cashflow') }}" method="GET" class="mt-4 sm:mt-0">
                <select name="year" onchange="this.form.submit()"
                    class="rounded-xl border-slate-100 py-2 px-6 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm font-black uppercase tracking-widest">
                    @foreach(range(date('Y'), 2024) as $y)
                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>Year {{ $y }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden border border-slate-50">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th
                                class="px-10 py-8 text-xs font-black uppercase tracking-[0.2em] text-slate-400 whitespace-nowrap">
                                Month</th>
                            <th
                                class="px-10 py-8 text-xs font-black uppercase tracking-[0.2em] text-emerald-500 text-right whitespace-nowrap">
                                Inflow (Income)</th>
                            <th
                                class="px-10 py-8 text-xs font-black uppercase tracking-[0.2em] text-rose-500 text-right whitespace-nowrap">
                                Outflow (Expense)</th>
                            <th
                                class="px-10 py-8 text-xs font-black uppercase tracking-[0.2em] text-slate-900 text-right whitespace-nowrap">
                                Net
                                Cashflow</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @php
                            $totalInflow = 0;
                            $totalOutflow = 0;
                        @endphp
                        @foreach(range(1, 12) as $m)
                            @php
                                $monthData = $cashflow->get($m);
                                $inflow = $monthData ? $monthData->income : 0;
                                $outflow = $monthData ? $monthData->expense : 0;
                                $net = $inflow - $outflow;
                                $totalInflow += $inflow;
                                $totalOutflow += $outflow;
                            @endphp
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-10 py-6 whitespace-nowrap">
                                    <span
                                        class="text-sm font-black text-slate-900 uppercase tracking-tight">{{ DateTime::createFromFormat('!m', $m)->format('F') }}</span>
                                </td>
                                <td
                                    class="px-10 py-6 text-right tabular-nums text-sm font-bold text-emerald-600 whitespace-nowrap">
                                    Rp {{ number_format($inflow, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-10 py-6 text-right tabular-nums text-sm font-bold text-rose-600 whitespace-nowrap">
                                    Rp {{ number_format($outflow, 0, ',', '.') }}
                                </td>
                                <td
                                    class="px-10 py-6 text-right tabular-nums text-sm font-black {{ $net >= 0 ? 'text-indigo-600' : 'text-rose-700' }} whitespace-nowrap">
                                    @if($net > 0) + @endif Rp {{ number_format($net, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-900 text-white">
                        <tr>
                            <td class="px-10 py-8 text-xs font-black uppercase tracking-widest italic whitespace-nowrap">
                                Annual Summary</td>
                            <td
                                class="px-10 py-8 text-right tabular-nums font-black text-emerald-400 text-lg whitespace-nowrap">
                                Rp {{ number_format($totalInflow, 0, ',', '.') }}
                            </td>
                            <td
                                class="px-10 py-8 text-right tabular-nums font-black text-rose-400 text-lg whitespace-nowrap">
                                Rp {{ number_format($totalOutflow, 0, ',', '.') }}
                            </td>
                            <td
                                class="px-10 py-8 text-right tabular-nums font-black text-white text-2xl underline decoration-indigo-500 decoration-4 underline-offset-8 whitespace-nowrap">
                                Rp {{ number_format($totalInflow - $totalOutflow, 0, ',', '.') }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection