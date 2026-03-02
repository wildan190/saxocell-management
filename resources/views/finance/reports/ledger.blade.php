@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-8 pb-24 px-4">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <a href="{{ route('finance.reports.index') }}" class="text-xs font-black uppercase tracking-widest text-indigo-600 hover:text-indigo-800 flex items-center gap-2 mb-4 transition-all">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                    Back to Reports
                </a>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900 uppercase">General Ledger</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium italic">Detailed record of all financial transactions.</p>
            </div>
            
            <form action="{{ route('finance.reports.ledger') }}" method="GET" class="mt-4 sm:mt-0 flex flex-wrap gap-4 items-end">
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Account</label>
                    <select name="account_id" class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm font-bold">
                        <option value="">All Accounts</option>
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $selectedAccountId == $acc->id ? 'selected' : '' }}>
                                {{ $acc->name }} ({{ $acc->warehouse ? $acc->warehouse->name : $acc->store->name }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Start Date</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">End Date</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm font-bold">
                </div>
                <button type="submit" class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-slate-800 transition-all shadow-lg active:scale-95">Filter</button>
            </form>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden border border-slate-50">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 whitespace-nowrap">Date</th>
                            <th class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 whitespace-nowrap">Account</th>
                            <th class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 whitespace-nowrap">Title / Category</th>
                            <th class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right whitespace-nowrap">Debit (In)</th>
                            <th class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right whitespace-nowrap">Credit (Out)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 italic">
                        @forelse($transactions as $tx)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6 text-sm text-slate-500 font-bold tabular-nums whitespace-nowrap">{{ $tx->transaction_date->format('d M Y') }}</td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <p class="text-sm font-black text-slate-900">{{ $tx->account->name }}</p>
                                    <p class="text-[10px] uppercase font-bold text-slate-400">{{ $tx->account->warehouse ? $tx->account->warehouse->name : $tx->account->store->name }}</p>
                                </td>
                                <td class="px-8 py-6 min-w-[200px]">
                                    <p class="text-sm font-black text-slate-900 mb-1">{{ $tx->title }}</p>
                                    <span class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] font-black uppercase tracking-widest text-slate-600 ring-1 ring-inset ring-slate-200">
                                        {{ $tx->category }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right tabular-nums whitespace-nowrap">
                                    @if($tx->type === 'income')
                                        <span class="text-sm font-black text-emerald-600">+ Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300 font-bold">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right tabular-nums whitespace-nowrap">
                                    @if($tx->type === 'expense')
                                        <span class="text-sm font-black text-rose-600">- Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-slate-300 font-bold">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center text-slate-400 italic">No ledger entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-6 border-t border-slate-100">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
@endsection
