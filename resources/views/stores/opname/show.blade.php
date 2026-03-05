@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 pb-20">
        <div class="sm:flex sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('stores.opname.index', $store) }}"
                    class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">Stock Opname Summary</h1>
                    <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $opname->reference_number }} • {{ $store->name }}</p>
                </div>
            </div>

            @if($opname->status === 'pending')
                <a href="{{ route('stores.opname.scan', [$store, $opname]) }}"
                    class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-xl shadow-indigo-100 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                    Continue Scanning
                </a>
            @else
                <div class="flex items-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl border border-emerald-100 italic">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-xs font-black uppercase tracking-widest">Session Completed</span>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Created By</p>
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500 uppercase">
                        {{ substr($opname->user->name, 0, 2) }}
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">{{ $opname->user->name }}</p>
                        <p class="text-[10px] text-slate-400 font-bold">{{ $opname->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Status</p>
                <div class="flex items-center gap-3">
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest
                        {{ $opname->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $opname->status }}
                    </span>
                    @if($opname->completed_at)
                        <p class="text-[10px] text-slate-400 font-bold italic">on {{ $opname->completed_at->format('M d, Y H:i') }}</p>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Total Items</p>
                <p class="text-3xl font-black text-slate-900 tracking-tighter">{{ $opname->items->count() }}</p>
            </div>
        </div>

        @if($opname->notes)
            <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-100 italic">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Notes</p>
                <p class="text-sm text-slate-600 font-medium">{{ $opname->notes }}</p>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-50">
                <h3 class="text-lg font-bold text-slate-900 uppercase">Detailed Report</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Product</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">System Stock</th>
                        <th class="px-8 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Physical Count</th>
                        <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Difference</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($opname->items as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-5">
                                <p class="text-sm font-black text-slate-900 capitalize">{{ $item->product->name }}</p>
                                <p class="text-[10px] font-mono text-slate-400 uppercase tracking-tighter">{{ $item->product->sku }}</p>
                            </td>
                            <td class="px-8 py-5 text-center text-sm font-bold text-slate-500">{{ $item->system_stock }}</td>
                            <td class="px-8 py-5 text-center text-sm font-black text-indigo-600">{{ $item->physical_stock }}</td>
                            <td class="px-8 py-5 text-right">
                                <span class="px-3 py-1 rounded-lg text-xs font-black
                                    {{ $item->difference < 0 ? 'bg-rose-50 text-rose-600' : ($item->difference > 0 ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-50 text-slate-400') }}">
                                    {{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
