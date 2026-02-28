@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('warehouses.index') }}" class="hover:text-slate-700">Warehouses</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('finance.accounts.index', $warehouse) }}"
                                class="hover:text-slate-700">{{ $warehouse->name }}</a></li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Goods Receipts</h1>
                <p class="mt-2 text-sm text-slate-700">Tracking incoming inventory for <span
                        class="font-semibold text-indigo-600">{{ $warehouse->name }}</span>.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex space-x-3">
                <a href="{{ route('inventory.goods-returns.index', $warehouse) }}"
                    class="block rounded-xl bg-white border border-slate-200 px-4 py-3 text-center text-sm font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all active:scale-[0.98]">
                    Return History
                </a>
                <a href="{{ route('inventory.goods-receipts.create', $warehouse) }}"
                    class="block rounded-xl bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">
                    New Receipt
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
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Receipt #</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Sender</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($receipts as $receipt)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm font-medium text-slate-900">
                                {{ $receipt->received_date }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-indigo-600">
                                {{ $receipt->receipt_number }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                {{ $receipt->sender_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                <a href="{{ route('inventory.goods-receipts.show', [$warehouse, $receipt]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-500 italic">No receipts recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection