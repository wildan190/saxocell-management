@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-black leading-tight tracking-tight text-slate-900">Generate Payroll</h1>
                <p class="mt-2 text-sm text-slate-700">Select the period and dates for this payroll cycle.</p>
            </div>
        </div>

        <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/50 sm:rounded-[32px] border border-slate-100">
            <form action="{{ route('hrm.payroll.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="month"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Month</label>
                        <select id="month" name="month" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all">
                            @foreach(range(1, 12) as $m)
                                <option value="{{ $m }}" {{ date('n') == $m ? 'selected' : '' }}>
                                    {{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="year"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Year</label>
                        <input id="year" name="year" type="number" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all"
                            value="{{ date('Y') }}">
                    </div>

                    <div>
                        <label for="period_start"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Start
                            Date</label>
                        <input id="period_start" name="period_start" type="date" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all"
                            value="{{ date('Y-m-01') }}">
                    </div>

                    <div>
                        <label for="period_end"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">End
                            Date</label>
                        <input id="period_end" name="period_end" type="date" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all"
                            value="{{ date('Y-m-t') }}">
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-4 pt-6 border-t border-slate-50">
                    <a href="{{ route('hrm.payroll.index') }}"
                        class="text-sm font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                    <button type="submit"
                        class="rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-[0.98] uppercase tracking-widest">
                        Process Calculation
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection