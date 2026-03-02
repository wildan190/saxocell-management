@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-black leading-tight tracking-tight text-slate-900">Request Leave</h1>
                <p class="mt-2 text-sm text-slate-700 font-medium tracking-tight">Submit your time-off request for approval.
                </p>
            </div>
        </div>

        <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/50 sm:rounded-[32px] border border-slate-100">
            <form action="{{ route('ess.leaves.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="start_date"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Start
                            Date</label>
                        <input id="start_date" name="start_date" type="date" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all"
                            value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label for="end_date"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">End
                            Date</label>
                        <input id="end_date" name="end_date" type="date" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all"
                            value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <div>
                    <label for="reason"
                        class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Reason for
                        Leave</label>
                    <textarea id="reason" name="reason" rows="4" required
                        class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all"
                        placeholder="Please explain why you need time off..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-x-4 pt-6 border-t border-slate-50 text-font-medium">
                    <a href="{{ route('ess.leaves.index') }}"
                        class="text-sm font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                    <button type="submit"
                        class="rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-[0.98] uppercase tracking-widest">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection