@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="sm:flex sm:items-center sm:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">Employees</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Manage your workforce, their roles, and salary structures.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <a href="{{ route('hrm.employees.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-95 uppercase tracking-widest">
                    Add Employee
                </a>
            </div>
        </div>

        <!-- Employee List Card -->
        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">Employee</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">EMP ID</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">Basic Salary</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">Benefits</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($employees as $employee)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="h-12 w-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400">
                                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-black text-slate-900">{{ $employee->user->name }}</p>
                                            <p class="text-sm text-slate-500">{{ $employee->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center rounded-xl bg-slate-100 px-3 py-1 text-xs font-black text-slate-900 uppercase tracking-widest ring-1 ring-inset ring-slate-200">
                                        {{ $employee->employee_id }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 font-bold text-slate-900">
                                    Rp {{ number_format($employee->basic_salary, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex gap-2">
                                        @if($employee->tax_pph21) <span class="text-[10px] font-black uppercase tracking-tighter bg-rose-50 text-rose-600 px-2 py-0.5 rounded-lg border border-rose-100">PPH21</span> @endif
                                        @if($employee->jht) <span class="text-[10px] font-black uppercase tracking-tighter bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-lg border border-indigo-100">JHT</span> @endif
                                        @if($employee->bpjs) <span class="text-[10px] font-black uppercase tracking-tighter bg-emerald-50 text-emerald-600 px-2 py-0.5 rounded-lg border border-emerald-100">BPJS</span> @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="#" class="p-2 text-slate-400 hover:text-indigo-600 transition-colors">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" /></svg>
                                        </a>
                                        <form action="#" method="POST" class="inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors">
                                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center">
                                    <div class="inline-flex items-center justify-center p-8 bg-slate-50 rounded-[40px] mb-6">
                                        <svg class="h-16 w-16 text-slate-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 italic">No employees found.</h3>
                                    <p class="text-slate-500 font-medium mt-2">Start by onboarding your first team member.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
