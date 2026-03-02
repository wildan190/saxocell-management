@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="sm:flex sm:items-center sm:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">Payroll Periods</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Manage monthly payroll cycles and generate payslips.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <a href="{{ route('hrm.payroll.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-95 uppercase tracking-widest">
                    Generate New Payroll
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Period</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Range</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Status</th>
                            <th scope="col"
                                class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($payrolls as $payroll)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="font-black text-slate-900">{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }}
                                        {{ $payroll->year }}</p>
                                </td>
                                <td class="px-8 py-6 text-sm font-medium text-slate-600">
                                    {{ $payroll->period_start }} <span class="text-slate-300 mx-2">→</span>
                                    {{ $payroll->period_end }}
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-black uppercase tracking-widest {{ $payroll->status === 'published' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/10' : 'bg-amber-50 text-amber-700 ring-amber-600/10' }} ring-1 ring-inset">
                                        {{ $payroll->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('hrm.payroll.show', $payroll) }}"
                                        class="text-indigo-600 font-black text-xs uppercase tracking-widest hover:text-indigo-500 transition-all">View
                                        Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-8 py-24 text-center text-slate-400 italic">No payroll periods found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection