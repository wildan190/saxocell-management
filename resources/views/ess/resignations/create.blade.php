@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-black leading-tight tracking-tight text-slate-900">Submit Resignation</h1>
                <p class="mt-2 text-sm text-slate-700 font-medium tracking-tight">We're sorry to see you go. Please provide
                    your final working day and reason.</p>
            </div>
        </div>

        <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/50 sm:rounded-[40px] border border-slate-100 italic">
            <form action="{{ route('ess.resignations.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="resignation_date"
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Proposed Final
                        Working Day</label>
                    <input id="resignation_date" name="resignation_date" type="date" required
                        class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm transition-all"
                        value="{{ date('Y-m-d', strtotime('+30 days')) }}">
                </div>

                <div>
                    <label for="reason"
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Reason for
                        Resignation</label>
                    <textarea id="reason" name="reason" rows="6" required
                        class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-rose-600 sm:text-sm transition-all"
                        placeholder="Please share your reasons for leaving..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-x-4 pt-6 border-t border-slate-50">
                    <a href="{{ route('ess.resignations.index') }}"
                        class="text-sm font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                    <button type="submit"
                        class="rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-rose-600 transition-all active:scale-[0.98] uppercase tracking-widest">
                        Submit Final Notice
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection