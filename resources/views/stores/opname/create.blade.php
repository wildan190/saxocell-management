@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8 pb-20">
        <div class="flex items-center gap-4">
            <a href="{{ route('stores.opname.index', $store) }}"
                class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-black tracking-tight text-slate-900 uppercase">New Opname Session</h1>
                <p class="text-sm text-slate-500 font-bold uppercase tracking-widest mt-1">{{ $store->name }}</p>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
            <form action="{{ route('stores.opname.store', $store) }}" method="POST" class="space-y-8">
                @csrf

                <div>
                    <label class="block text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-3">Notes
                        (Optional)</label>
                    <textarea name="notes" rows="4"
                        class="block w-full rounded-2xl border-slate-100 bg-slate-50/50 px-5 py-4 text-sm font-bold text-slate-900 placeholder-slate-300 focus:ring-indigo-600 focus:border-indigo-600 transition-all"
                        placeholder="Purpose of this stock opname..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full rounded-2xl bg-slate-900 py-5 text-sm font-black text-white shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all active:scale-[0.98] uppercase tracking-widest">
                        Start Counting
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-indigo-50 rounded-[2rem] p-8 border border-indigo-100">
            <div class="flex gap-4">
                <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="text-sm font-black text-indigo-900 uppercase tracking-widest mb-1">Important Note</h4>
                    <p class="text-xs text-indigo-700 font-medium leading-relaxed italic">
                        By starting a session, you will be able to scan products and adjust their physical stock levels.
                        Inventory changes will only take effect after you "Finalize" the session.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection