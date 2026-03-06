@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('stores.index') }}" class="hover:text-slate-700">Stores</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('stores.show', $store) }}" class="hover:text-slate-700">{{ $store->name }}</a>
                        </li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Goods Receipts</h1>
                <p class="mt-2 text-sm text-slate-700">Tracking incoming inventory for Store <span
                        class="font-semibold text-indigo-600">{{ $store->name }}</span>.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex space-x-3">
                <a href="{{ route('stores.goods-receipts.create', $store) }}"
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
        {{-- Filters --}}
        <div class="bg-white rounded-2xl p-4 ring-1 ring-slate-100 shadow-sm mb-6 flex flex-wrap items-center gap-4">
            <form action="{{ route('stores.goods-receipts.index', $store) }}" method="GET" class="flex flex-wrap items-center gap-4 w-full">
                <div class="flex-1 min-w-[200px]">
                    <select name="sale_status" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 text-sm font-bold focus:ring-indigo-600 focus:border-indigo-600">
                        <option value="">-- Semua Status Jual --</option>
                        <option value="sold" {{ request('sale_status') == 'sold' ? 'selected' : '' }}>Semua Terjual</option>
                        <option value="unsold" {{ request('sale_status') == 'unsold' ? 'selected' : '' }}>Belum Terjual (Ada Stok)</option>
                    </select>
                </div>
                @if(request('sale_status'))
                    <a href="{{ route('stores.goods-receipts.index', $store) }}" class="text-xs font-black text-rose-500 uppercase tracking-widest hover:text-rose-700">Reset</a>
                @endif
            </form>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-slate-100 rounded-2xl overflow-hidden mt-8">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Receipt #</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Admin</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Sender</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Status Jual</th>
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
                                {{ $receipt->admin->name ?? 'System' }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                {{ $receipt->sender_name }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm">
                                @php
                                    $saleStatus = $receipt->sale_status;
                                @endphp
                                @if($saleStatus === 'sold')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-black uppercase bg-indigo-100 text-indigo-700">Terjual</span>
                                @elseif($saleStatus === 'partial')
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-black uppercase bg-amber-100 text-amber-700">Parsial</span>
                                @else
                                    <span class="inline-flex rounded-full px-2.5 py-0.5 text-[11px] font-black uppercase bg-slate-100 text-slate-500">Belum Terjual</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                <a href="{{ route('stores.goods-receipts.show', [$store, $receipt]) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-semibold">View Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-500 italic">No receipts recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($receipts->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $receipts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection