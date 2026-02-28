@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center gap-4">
            <a href="{{ route('inventory.opname.index') }}"
                class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Start New Stock Opname</h1>
                <p class="text-sm text-slate-500 mt-1">Select the warehouse location to begin counting.</p>
            </div>
        </div>

        <form action="{{ route('inventory.opname.store') }}" method="POST" class="space-y-8">
            @csrf
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden p-8 md:p-10 space-y-8">
                <div class="space-y-2">
                    <label for="warehouse_id"
                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Select Warehouse</label>
                    <select name="warehouse_id" id="warehouse_id" required {{ isset($selectedWarehouseId) && $selectedWarehouseId ? 'disabled' : '' }}
                        class="block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all hover:border-slate-300 {{ isset($selectedWarehouseId) && $selectedWarehouseId ? 'bg-slate-50 cursor-not-allowed' : '' }}">
                        <option value="">Select a warehouse...</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" {{ (isset($selectedWarehouseId) && $selectedWarehouseId == $warehouse->id) ? 'selected' : '' }}>{{ $warehouse->name }}</option>
                        @endforeach
                    </select>
                    @if(isset($selectedWarehouseId) && $selectedWarehouseId)
                        <input type="hidden" name="warehouse_id" value="{{ $selectedWarehouseId }}">
                    @endif
                </div>

                <div class="space-y-2">
                    <label for="notes" class="block text-xs font-bold text-slate-400 uppercase tracking-widest">Session
                        Notes</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="block w-full px-4 py-3 rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all hover:border-slate-300"
                        placeholder="e.g. Monthly audit Feb 2026..."></textarea>
                </div>

                <div class="flex justify-end pt-4 border-t border-slate-50">
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-10 py-3 text-sm font-bold text-white shadow-lg shadow-indigo-100 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                        Initialize Session
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection