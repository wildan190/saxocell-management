@extends('layouts.app')

@section('content')
    <div
        class="max-w-2xl mx-auto py-12 px-4 sm:px-6 lg:px-8 bg-white shadow-2xl rounded-[48px] border border-slate-100 print:shadow-none print:border-none print:m-0 print:p-0">
        <!-- Header -->
        <div class="flex justify-between items-start mb-12 border-b border-slate-50 pb-8">
            <div class="print:block">
                <h1 class="text-4xl font-black text-slate-900 italic tracking-tighter">SAXOCELL</h1>
                <p class="text-xs font-black uppercase tracking-[0.3em] text-indigo-500">Official Payslip</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-black text-slate-900">
                    {{ date('F Y', mktime(0, 0, 0, $payrollItem->payroll->month, 1)) }}</h2>
                <p class="text-sm text-slate-500 font-medium tracking-tight">Period:
                    {{ $payrollItem->payroll->period_start }} - {{ $payrollItem->payroll->period_end }}</p>
            </div>
        </div>

        <!-- Employee Info -->
        <div class="grid grid-cols-2 gap-8 mb-12 bg-slate-50/50 p-8 rounded-3xl border border-slate-50">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Employee Name</p>
                <p class="text-lg font-black text-slate-900">{{ $payrollItem->user->name }}</p>
            </div>
            <div class="text-right">
                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Employee ID</p>
                <p class="text-lg font-black text-slate-900">{{ $payrollItem->user->employee->employee_id }}</p>
            </div>
        </div>

        <!-- Attendance Info -->
        <div class="mb-12 border-b border-slate-50 pb-8 px-4 flex justify-between items-center">
            <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900">Attendance</h3>
            <p class="text-sm font-black text-indigo-600 italic tracking-tight underline decoration-indigo-200 decoration-4 underline-offset-4">{{ $payrollItem->total_days }} Working Days Recorded</p>
        </div>

        <!-- Remuneration Details -->
        <div class="space-y-6 mb-12">
            <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900 border-l-4 border-indigo-600 pl-4">
                Earnings</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center px-4">
                    <span class="text-sm text-slate-500 font-medium">Basic Salary</span>
                    <span class="text-sm font-bold text-slate-900">Rp
                        {{ number_format($payrollItem->basic_salary, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center px-4">
                    <span class="text-sm text-slate-500 font-medium">Fixed Allowance</span>
                    <span class="text-sm font-bold text-slate-900">Rp
                        {{ number_format($payrollItem->allowance, 0, ',', '.') }}</span>
                </div>
            </div>

            <h3 class="text-xs font-black uppercase tracking-[0.3em] text-slate-900 border-l-4 border-rose-600 pl-4 pt-4">
                Deductions</h3>
            <div class="space-y-4 px-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-slate-500 font-medium">Miscellaneous</span>
                    <span class="text-sm font-bold text-rose-600">- Rp
                        {{ number_format($payrollItem->deductions, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Total -->
        <div
            class="bg-slate-900 rounded-[32px] p-8 flex justify-between items-center text-white mb-12 shadow-xl shadow-indigo-100">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-white/50 mb-1">Take Home Pay</p>
                <p class="text-xs text-indigo-400 font-bold tracking-tight italic tabular-nums">Net Salary Disbursement</p>
            </div>
            <div class="text-right">
                <p class="text-3xl font-black tracking-tighter tabular-nums">Rp
                    {{ number_format($payrollItem->net_salary, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center print:hidden border-t border-slate-50 pt-8">
            <button onclick="window.print()"
                class="bg-indigo-600 text-white px-8 py-4 rounded-2xl font-black uppercase tracking-[0.2em] text-xs shadow-xl shadow-indigo-100 hover:scale-105 transition-all active:scale-95">Download
                PDF</button>
        </div>

        <div class="hidden print:block text-center text-[10px] text-slate-300 font-bold uppercase tracking-widest mt-12">
            This is a computer-generated document. No signature required.
        </div>
    </div>
@endsection