@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Stores</h1>
                <p class="mt-2 text-sm text-slate-700">A list of all stores in your account including their name,
                    address, and phone number.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('stores.create') }}"
                    class="block rounded-xl bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-[0.98]">
                    Add Store
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($stores as $store)
                <a href="{{ route('stores.show', $store) }}"
                    class="group relative flex flex-col overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-100 transition-all hover:shadow-md hover:ring-indigo-200 active:scale-[0.98]">
                    <div class="flex items-center justify-between">
                        <div
                            class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600 transition-colors group-hover:bg-indigo-600 group-hover:text-white">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H22.25m-12.917-14.746 3.917-3.917a1.125 1.125 0 0 1 1.591 0L19.083 6.254M1.75 10.5c.621 0 1.125-.504 1.125-1.125V3a.75.75 0 0 1 .75-.75h14.25a.75.75 0 0 1 .75.75v6.375c0 .621.504 1.125 1.125 1.125M1.75 10.5h20.5m-20.5 0v10.5h20.5V10.5" />
                            </svg>
                        </div>
                    </div>

                    <div class="mt-6">
                        <h3 class="text-lg font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">
                            {{ $store->name }}
                        </h3>
                        <div class="mt-1 flex items-center gap-1.5 text-sm text-slate-500">
                            <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ $store->address ?? 'No address provided' }}
                        </div>
                    </div>

                    <div
                        class="mt-6 grid grid-cols-2 gap-4 border-t border-slate-50 pt-4 text-xs font-semibold uppercase tracking-wider text-slate-400">
                        <div>
                            <p>Phone</p>
                            <p class="mt-1 text-sm font-bold text-slate-700">{{ $store->phone ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p>Accounts</p>
                            <p class="mt-1 text-sm font-bold text-slate-700">{{ $store->financeAccounts->count() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full py-12 text-center text-sm text-slate-500 italic">
                    No stores found. Click "Add Store" to create one.
                </div>
            @endforelse
        </div>
    </div>
@endsection