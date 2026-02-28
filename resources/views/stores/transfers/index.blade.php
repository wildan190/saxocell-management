@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="flex items-center gap-3">
                    <a href="{{ route('stores.show', $store) }}"
                        class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Inter-Store Transfers -
                        {{ $store->name }}</h1>
                </div>
                <p class="mt-2 text-sm text-slate-700">Manage goods transfers between this store and other locations.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('stores.transfers.create', $store) }}"
                    class="block rounded-xl bg-indigo-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition-all active:scale-[0.98]">
                    New Transfer
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100">
                <div class="flex">
                    <div class="flex-shrink-0 text-green-400">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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

        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Transfer #</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">From
                            / To</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Status</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date
                        </th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transfers as $transfer)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-slate-900">{{ $transfer->transfer_number }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs font-bold text-slate-900">
                                                    {{ $transfer->from_store_id == $store->id ? 'To: ' . $transfer->toStore->name : 'From: ' . $transfer->fromStore->name }}
                                                </span>
                                                <span class="text-[10px] text-slate-400 uppercase">
                                                    {{ $transfer->from_store_id == $store->id ? 'Outgoing' : 'Incoming' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $statusColors = [
                                                    'pending' => 'bg-amber-100 text-amber-700',
                                                    'shipped' => 'bg-blue-100 text-blue-700',
                                                    'received' => 'bg-green-100 text-green-700',
                                                ];
                                            @endphp
                         <span
                                                class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $statusColors[$transfer->status] ?? 'bg-slate-100 text-slate-700' }}">
                                                {{ $transfer->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-500">
                                            {{ $transfer->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('stores.transfers.show', [$store, $transfer]) }}"
                                                class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500 italic">
                                No transfers found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection