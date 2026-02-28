@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Stock Transfers</h1>
                <p class="mt-2 text-sm text-slate-700">Track and manage product movements between your warehouse locations.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <a href="{{ route('inventory.transfers.create') }}"
                    class="block rounded-xl bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">
                    New Transfer
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

        <div class="bg-white shadow-sm ring-1 ring-slate-100 rounded-2xl overflow-hidden mt-8">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Transfer #</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">From</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">To</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($transfers as $transfer)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-500">
                                {{ $transfer->transfer_date }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-indigo-600">
                                {{ $transfer->transfer_number }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900">
                                <span
                                    class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                    {{ $transfer->fromWarehouse->name }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900">
                                <span
                                    class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/10">
                                    {{ $transfer->toWarehouse->name }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                <a href="{{ route('inventory.transfers.show', $transfer) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold underline decoration-2 underline-offset-4">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 italic">No transfers recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection