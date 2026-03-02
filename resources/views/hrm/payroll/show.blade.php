@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="sm:flex sm:items-center sm:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">
                    Payroll: {{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}
                </h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Review salary allocations before publishing.</p>
            </div>
            @if($payroll->status === 'draft')
                <div class="mt-4 sm:ml-16 sm:mt-0">
                    <form action="{{ route('hrm.payroll.publish', $payroll) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-emerald-500 transition-all active:scale-95 uppercase tracking-widest">
                            Publish & Notify
                        </button>
                    </form>
                </div>
            @endif
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Employee</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-center">Days</th>
                            <th scope="col"
                                class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Basic Salary</th>
                            <th scope="col"
                                class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Allowance</th>
                            <th scope="col"
                                class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Deductions</th>
                            <th scope="col"
                                class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Net Salary</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($payroll->items as $item)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <p class="font-black text-slate-900 border-l-4 border-indigo-500 pl-4 transition-all group-hover:pl-6">{{ $item->user->name }}</p>
                                </td>
                                <td class="px-8 py-6 text-center font-bold text-slate-900 italic tabular-nums">{{ $item->total_days }}</td>
                                <td class="px-8 py-6 text-right font-medium text-slate-600">Rp {{ number_format($item->basic_salary, 0, ',', '.') }}</td>
                                <td class="px-8 py-6 text-right font-medium text-slate-600">Rp
                                    {{ number_format($item->allowance, 0, ',', '.') }}</td>
                                <td class="px-8 py-6 text-right font-medium text-rose-600">- Rp
                                    {{ number_format($item->deductions, 0, ',', '.') }}</td>
                                <td class="px-8 py-6 text-right font-black text-slate-900 text-lg">
                                    Rp {{ number_format($item->net_salary, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection