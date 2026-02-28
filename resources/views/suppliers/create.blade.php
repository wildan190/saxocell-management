@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('suppliers.index') }}" class="hover:text-slate-700">Suppliers</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">Add Supplier</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Add Supplier</h1>
                <p class="mt-2 text-sm text-slate-700">Onboard a new supplier by providing their professional and contact
                    information.</p>
            </div>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-6">
            @csrf
            <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 p-8 space-y-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                    <!-- Name -->
                    <div class="sm:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-slate-900">Supplier Name</label>
                        <input type="text" name="name" id="name" required value="{{ old('name') }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="Full name or individual name">
                        @error('name') <p class="mt-1 text-xs text-rose-600">{{ $message }}</p> @enderror
                    </div>

                    <!-- Company -->
                    <div class="sm:col-span-1">
                        <label for="company" class="block text-sm font-semibold text-slate-900">Company Name</label>
                        <input type="text" name="company" id="company" value="{{ old('company') }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="Entity or organization name">
                    </div>

                    <!-- NIK -->
                    <div class="sm:col-span-1">
                        <label for="nik" class="block text-sm font-semibold text-slate-900">NIK (National ID)</label>
                        <input type="text" name="nik" id="nik" value="{{ old('nik') }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="Registry number">
                    </div>

                    <!-- Email -->
                    <div class="sm:col-span-1">
                        <label for="email" class="block text-sm font-semibold text-slate-900">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="supplier@example.com">
                    </div>

                    <!-- Contact -->
                    <div class="sm:col-span-1">
                        <label for="contact" class="block text-sm font-semibold text-slate-900">Contact Number</label>
                        <input type="text" name="contact" id="contact" value="{{ old('contact') }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="+62 ...">
                    </div>

                    <!-- Address -->
                    <div class="sm:col-span-2">
                        <label for="address" class="block text-sm font-semibold text-slate-900">Physical Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="Office or warehouse location">{{ old('address') }}</textarea>
                    </div>

                    <!-- Description -->
                    <div class="sm:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-slate-900">Description /
                            Notes</label>
                        <textarea name="description" id="description" rows="3"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="Supplies type, reliability, terms, etc...">{{ old('description') }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-100">
                    <a href="{{ route('suppliers.index') }}"
                        class="px-6 py-2 text-sm font-semibold text-slate-700">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-8 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all shadow-indigo-200">
                        Create Supplier
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection