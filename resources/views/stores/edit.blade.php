@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="md:flex md:items-center md:justify-between mb-8">
            <div class="flex-1 min-w-0">
                <h2 class="text-3xl font-bold leading-7 text-slate-900 sm:truncate tracking-tight">Edit Store:
                    {{ $store->name }}</h2>
                <p class="mt-2 text-sm text-slate-500">Update the retail location details and contact information.
                </p>
            </div>
        </div>

        <form action="{{ route('stores.update', $store) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')
            <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
                <div class="p-8 space-y-6">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-900">Store Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $store->name) }}" required
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="e.g. SaxoCell City Mall">
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-semibold text-slate-900">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="Full location address...">{{ old('address', $store->address) }}</textarea>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-semibold text-slate-900">Phone Number</label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone', $store->phone) }}"
                            class="mt-2 block w-full rounded-xl border-slate-200 px-4 py-3 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"
                            placeholder="e.g. +62 812-3456-7890">
                    </div>
                </div>

                <div class="bg-slate-50 px-8 py-6 flex items-center justify-end gap-x-4">
                    <a href="{{ route('stores.show', $store) }}"
                        class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700 transition-colors">Cancel</a>
                    <button type="submit"
                        class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-[0.98]">
                        Update Store
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection