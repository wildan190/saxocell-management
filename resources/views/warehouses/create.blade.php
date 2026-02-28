@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Add New Warehouse</h1>
                <p class="mt-2 text-sm text-slate-700">Enter the details of the new warehouse location.</p>
            </div>
        </div>

        <div class="bg-white px-8 py-10 shadow-xl shadow-slate-200/50 sm:rounded-2xl border border-slate-100">
            <form action="{{ route('warehouses.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 gap-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold leading-6 text-slate-900">Warehouse
                            Name</label>
                        <div class="mt-2 text-red">
                            <input id="name" name="name" type="text" required
                                class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all @error('name') ring-red-500 @enderror"
                                placeholder="e.g. Central Hub" value="{{ old('name') }}">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-semibold leading-6 text-slate-900">Location</label>
                        <div class="mt-2">
                            <input id="location" name="location" type="text" required
                                class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all @error('location') ring-red-500 @enderror"
                                placeholder="e.g. Jakarta, Indonesia" value="{{ old('location') }}">
                            @error('location')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="capacity" class="block text-sm font-semibold leading-6 text-slate-900">Capacity
                            (units)</label>
                        <div class="mt-2">
                            <input id="capacity" name="capacity" type="number"
                                class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all @error('capacity') ring-red-500 @enderror"
                                placeholder="e.g. 5000" value="{{ old('capacity') }}">
                            @error('capacity')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-semibold leading-6 text-slate-900">Description</label>
                        <div class="mt-2">
                            <textarea id="description" name="description" rows="4"
                                class="block w-full rounded-xl border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-x-4 pt-6 border-t border-slate-100">
                    <a href="{{ route('warehouses.index') }}"
                        class="text-sm font-semibold leading-6 text-slate-700 hover:text-slate-500 transition-colors">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-[0.98]">
                        Save Warehouse
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection