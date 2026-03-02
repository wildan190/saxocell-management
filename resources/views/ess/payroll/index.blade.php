@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-12 pb-24">
        <div class="px-4">
            <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">My Payslips</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">View and download your monthly remuneration
                details.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($payrollItems as $item)
                <div
                    class="bg-white rounded-[40px] p-10 shadow-xl shadow-slate-200/50 border border-slate-100 group hover:-translate-y-2 transition-all">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-indigo-500 mb-1">Payroll Period</p>
                            <h3 class="text-2xl font-black text-slate-900">
                                {{ date('F', mktime(0, 0, 0, $item->payroll->month, 1)) }} {{ $item->payroll->year }}</h3>
                        </div>
                        <div
                            class="h-12 w-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-indigo-600 group-hover:text-white transition-all">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .401.127.773.344 1.077l.216.304c.145.205.145.474 0 .68l-.216.304c-.217.304-.344.676-.344 1.077 0 .231.035.454.1.664m-.1-4.77h4.77c.621 0 1.125.504 1.125 1.125v4.77m-1.125-4.77l-4.77 4.77m0-4.77v4.77m4.77-4.77v4.77" />
                            </svg>
                        </div>
                    </div>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500 font-medium">Net Salary</span>
                            <span class="text-slate-900 font-black">Rp
                                {{ number_format($item->net_salary, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <a href="{{ route('ess.payroll.show', $item) }}"
                            class="flex-1 bg-slate-900 text-white py-3 rounded-2xl text-center text-xs font-black uppercase tracking-widest hover:bg-slate-800 transition-all">View
                            Detail</a>
                        <button onclick="window.print()"
                            class="px-3 bg-slate-50 text-slate-400 rounded-2xl hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center">
                    <div class="inline-flex items-center justify-center p-8 bg-slate-50 rounded-[40px] mb-6">
                        <svg class="h-16 w-16 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .401.127.773.344 1.077l.216.304c.145.205.145.474 0 .68l-.216.304c-.217.304-.344.676-.344 1.077 0 .231.035.454.1.664m-.1-4.77h4.77c.621 0 1.125.504 1.125 1.125v4.77m-1.125-4.77l-4.77 4.77m0-4.77v4.77m4.77-4.77v4.77" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-900 italic">No payslips available.</h3>
                    <p class="text-slate-500 font-medium mt-2">Payslips appear here once published by HRM.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection