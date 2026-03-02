@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-black leading-tight tracking-tight text-slate-900">Onboard New Employee</h1>
                <p class="mt-2 text-sm text-slate-700">Fill in the details to create a new employee account and profile.</p>
            </div>
        </div>

        <form action="{{ route('hrm.employees.store') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Account Information -->
            <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/50 sm:rounded-[32px] border border-slate-100">
                <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white text-xs">1</span>
                    Account Credentials
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="name"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Full
                            Name</label>
                        <input id="name" name="name" type="text" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all @error('name') ring-red-500 @enderror"
                            placeholder="e.g. Ahmad Dani" value="{{ old('name') }}">
                        @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="email"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Email
                            Address</label>
                        <input id="email" name="email" type="email" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all @error('email') ring-red-500 @enderror"
                            placeholder="dani@example.com" value="{{ old('email') }}">
                        @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Initial
                            Password</label>
                        <input id="password" name="password" type="password" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all @error('password') ring-red-500 @enderror"
                            placeholder="Min. 8 characters">
                        @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="employee_id"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Employee
                            ID</label>
                        <input id="employee_id" name="employee_id" type="text" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all @error('employee_id') ring-red-500 @enderror"
                            placeholder="e.g. EMP-001" value="{{ old('employee_id', 'EMP-' . rand(100, 999)) }}">
                        @error('employee_id') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="position_role"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Position
                            Role</label>
                        <select id="position_role" name="position_role" required
                            class="block w-full rounded-2xl border-0 py-4 px-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all appearance-none bg-white @error('position_role') ring-red-500 @enderror">
                            <option value="" disabled selected>Select a role...</option>
                            @foreach($roles as $roleValue => $roleLabel)
                                <option value="{{ $roleValue }}" {{ old('position_role') == $roleValue ? 'selected' : '' }}>
                                    {{ ucfirst($roleLabel) }}
                                </option>
                            @endforeach
                        </select>
                        @error('position_role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Salary & Benefits -->
            <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/50 sm:rounded-[32px] border border-slate-100">
                <h2 class="text-xl font-black text-slate-900 mb-8 flex items-center gap-3">
                    <span
                        class="flex h-8 w-8 items-center justify-center rounded-xl bg-indigo-600 text-white text-xs">2</span>
                    Salary & Benefits
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label for="basic_salary"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Basic
                            Salary</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input id="basic_salary" name="basic_salary" type="number" required
                                class="block w-full rounded-2xl border-0 py-4 pl-14 pr-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all @error('basic_salary') ring-red-500 @enderror"
                                placeholder="0" value="{{ old('basic_salary') }}">
                        </div>
                        @error('basic_salary') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="allowance"
                            class="block text-xs font-black uppercase tracking-widest text-slate-400 mb-2 ml-1">Fixed
                            Allowance (Optional)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                            </div>
                            <input id="allowance" name="allowance" type="number"
                                class="block w-full rounded-2xl border-0 py-4 pl-14 pr-6 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm transition-all @error('allowance') ring-red-500 @enderror"
                                placeholder="0" value="{{ old('allowance') }}">
                        </div>
                        @error('allowance') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-2 grid grid-cols-2 md:grid-cols-4 gap-4">
                        <label
                            class="relative flex items-center p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="checkbox" name="tax_pph21" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <span class="ml-3 text-sm font-black text-slate-700 uppercase tracking-widest text-[10px]">PPH21
                                Tax</span>
                        </label>
                        <label
                            class="relative flex items-center p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="checkbox" name="jht" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <span class="ml-3 text-sm font-black text-slate-700 uppercase tracking-widest text-[10px]">JHT
                                (Insurance)</span>
                        </label>
                        <label
                            class="relative flex items-center p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="checkbox" name="bpjs" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <span class="ml-3 text-sm font-black text-slate-700 uppercase tracking-widest text-[10px]">BPJS
                                Health</span>
                        </label>
                        <label
                            class="relative flex items-center p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 cursor-pointer transition-all">
                            <input type="checkbox" name="overtime_eligible" value="1"
                                class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600">
                            <span
                                class="ml-3 text-sm font-black text-slate-700 uppercase tracking-widest text-[10px]">Overtime</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-x-6 pt-6">
                <a href="{{ route('hrm.employees.index') }}"
                    class="text-sm font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 transition-colors">Cancel</a>
                <button type="submit"
                    class="rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-[0.98] uppercase tracking-widest">
                    Complete Onboarding
                </button>
            </div>
        </form>
    </div>
@endsection