@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Warehouse to Store Transfers</h1>
                <p class="mt-2 text-sm text-slate-700">A historical log of all stock movements from central warehouses to
                    retail stores.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex-none">
                <a href="{{ route('inventory.warehouse-to-store.create') }}"
                    class="block rounded-xl bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">
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
                            Number</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date
                        </th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">From
                            Warehouse</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">To
                            Store</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Items</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transfers as $transfer)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $transfer->transfer_number }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600"
                                title="{{ $transfer->transfer_date->format('d M Y') }}">
                                {{ formatIndonesianRelativeTime($transfer->transfer_date) }}</td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-lg bg-orange-50 px-2 py-1 text-xs font-bold text-orange-700">
                                    {{ $transfer->fromWarehouse->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <span
                                    class="inline-flex items-center rounded-lg bg-indigo-50 px-2 py-1 text-xs font-bold text-indigo-700">
                                    {{ $transfer->toStore->name }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-sm font-bold text-slate-700">
                                {{ $transfer->items->count() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('inventory.warehouse-to-store.show', $transfer) }}"
                                    class="rounded-lg bg-slate-50 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-100 transition-colors">
                                    View Details
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500 italic">
                                No transfers recorded yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection