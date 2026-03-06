@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 pb-20">
        <!-- Header -->
        <div class="sm:flex sm:items-center justify-between">
            <div class="space-y-1">
                <div class="flex items-center gap-3">
                    <a href="{{ route('inventory.opname.index') }}"
                        class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900">{{ $opname->reference_number }}</h1>
                </div>
                <div class="flex items-center gap-4 mt-2">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status:</span>
                    <span
                        class="inline-flex items-center rounded-full px-4 py-1 text-xs font-black uppercase tracking-wider {{ $opname->status === 'completed' ? 'bg-green-100 text-green-700' : ($opname->status === 'pending' ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-700') }}">
                        {{ $opname->status }}
                    </span>
                </div>
            </div>

            @if($opname->status === 'pending')
                <a href="{{ route('inventory.opname.scan', $opname) }}"
                    class="rounded-xl bg-indigo-600 px-8 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                    Continue Counting
                </a>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                <!-- Data Table -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 md:p-10 border-b border-slate-50 flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">Adjustment Report</h2>
                        <span
                            class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $opname->items->count() }}
                            Products Checked</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th
                                        class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Product</th>
                                    <th
                                        class="px-8 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        System Stock</th>
                                    <th
                                        class="px-8 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Physical Stock</th>
                                    <th
                                        class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Difference</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                @foreach($opname->items as $item)
                                    <tr>
                                        <td class="px-8 py-4">
                                            <div class="space-y-0.5">
                                                <p class="text-sm font-bold text-slate-900 capitalize">
                                                    {{ $item->product->name }}</p>
                                                <p class="text-[10px] font-mono font-bold text-slate-400 tracking-widest">
                                                    {{ $item->product->sku }}</p>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-center text-sm font-medium text-slate-400">
                                            {{ $item->system_stock }}</td>
                                        <td class="px-8 py-4 text-center text-sm font-black text-indigo-600">
                                            {{ $item->physical_stock }}</td>
                                        <td class="px-8 py-4 text-right">
                                            @if($item->difference !== 0)
                                                <div
                                                    class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg {{ $item->difference < 0 ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                                    <span
                                                        class="text-sm font-black">{{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }}</span>
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                            d="{{ $item->difference < 0 ? 'M19 13l-7 7-7-7' : 'M5 11l7-7 7 7' }}" />
                                                    </svg>
                                                </div>
                                            @else
                                                <span class="text-sm font-bold text-slate-300">No Change</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="lg:col-span-1 space-y-8">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-10 space-y-8">
                    <h3 class="text-lg font-bold text-slate-900">Session Meta</h3>

                    <div class="space-y-6">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Warehouse</p>
                            <p class="font-bold text-slate-900">{{ $opname->warehouse->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Performed By</p>
                            <p class="font-bold text-slate-900">{{ $opname->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Started At</p>
                            <p class="font-bold text-slate-600">{{ $opname->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        @if($opname->completed_at)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Completed At</p>
                                <p class="font-bold text-emerald-600">{{ $opname->completed_at->format('M d, Y H:i') }}</p>
                            </div>
                        @endif
                        @if($opname->notes)
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Notes</p>
                                <p class="text-sm text-slate-500 italic leading-relaxed">"{{ $opname->notes }}"</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($opname->status === 'completed')
                    <div
                        class="bg-emerald-600 rounded-[2rem] p-8 text-white shadow-xl shadow-emerald-100 flex items-center gap-4">
                        <div class="h-12 w-12 rounded-2xl bg-white/20 flex items-center justify-center">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Stock Adjusted</p>
                            <p class="text-xs text-emerald-100">Inventory was updated on completion.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection