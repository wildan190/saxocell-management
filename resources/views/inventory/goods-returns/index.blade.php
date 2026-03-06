@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        @if($warehouse)
                            <li><a href="{{ route('warehouses.index') }}" class="hover:text-slate-700">Warehouses</a></li>
                            <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                        clip-rule="evenodd" />
                                </svg></li>
                            <li><a href="{{ route('inventory.goods-receipts.index', $warehouse) }}"
                                    class="hover:text-slate-700">Goods Receipts</a></li>
                            <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                        clip-rule="evenodd" />
                                </svg></li>
                        @else
                            <li><a href="{{ route('inventory.overview') }}" class="hover:text-slate-700">Inventory</a></li>
                            <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                        clip-rule="evenodd" />
                                </svg></li>
                        @endif
                        <li class="font-medium text-slate-900">Return History</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Rejected Goods / Return History
                </h1>
                <p class="mt-2 text-sm text-slate-700">History of rejected items and returns for <span
                        class="font-semibold text-indigo-600">{{ $warehouse ? $warehouse->name : 'All Warehouses' }}</span>.
                </p>
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-slate-100 rounded-2xl overflow-hidden mt-8">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col"
                            class="py-3.5 pl-6 pr-3 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            Date</th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            Return #</th>
                        @if(!$warehouse)
                            <th scope="col"
                                class="px-3 py-3.5 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                                Warehouse</th>
                        @endif
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            Items</th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            Resolution / Destination</th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            Status</th>
                        <th scope="col"
                            class="px-3 py-3.5 text-left text-[10px] font-bold text-slate-500 uppercase tracking-widest">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($returns as $return)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-500">
                                <span
                                    title="{{ $return->created_at->format('d M Y H:i') }}">{{ formatIndonesianRelativeTime($return->created_at) }}</span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-bold text-red-600">
                                {{ $return->return_number }}
                            </td>
                            @if(!$warehouse)
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                    <span
                                        class="bg-slate-100 px-2 py-1 rounded-lg text-xs font-semibold">{{ $return->goodsReceipt->warehouse->name }}</span>
                                </td>
                            @endif
                            <td class="px-3 py-4 text-sm text-slate-600">
                                <div class="flex flex-col gap-1">
                                    @foreach($return->items as $item)
                                        <span class="truncate max-w-[200px]" title="{{ $item->product->name }}">
                                            <span class="font-bold text-slate-900">{{ $item->quantity }}x</span>
                                            {{ $item->product->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                <div class="flex flex-col">
                                    <span class="font-bold text-slate-900">{{ $return->resolution }}</span>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-tighter">Receipt:
                                        {{ $return->goodsReceipt->receipt_number }}</span>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    {{ ucfirst($return->status) }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                <a href="{{ route('inventory.goods-returns.show', [$warehouse ?? $return->goodsReceipt->warehouse, $return]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold bg-indigo-50 px-3 py-2 rounded-lg transition-all active:scale-95 inline-block">View
                                    Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ !$warehouse ? '7' : '6' }}" class="py-12 text-center text-slate-500 italic">No
                                return history recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection