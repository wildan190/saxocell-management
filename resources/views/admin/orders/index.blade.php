@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Area -->
        <div
            class="lg:flex lg:items-center lg:justify-between p-8 bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 border-l-4 border-slate-900">
            <div class="min-w-0 flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li class="font-medium text-slate-900">Admin</li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">Marketplace Orders</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black leading-7 text-slate-900 sm:truncate sm:text-4xl sm:tracking-tight">
                    Customer Orders
                </h2>
                <p class="mt-1 text-sm text-slate-500">Overview of all transactions across all stores.</p>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-[32px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Order
                                No</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Store
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Total
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status
                            </th>
                            <th class="py-4 px-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="py-4 px-6 text-sm font-black text-slate-900">{{ $order->order_number }}</td>
                                <td class="py-4 px-6">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-800">{{ $order->customer_name }}</span>
                                        <span
                                            class="text-[10px] font-medium text-slate-400 italic">{{ $order->customer_contact }}</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-600 font-bold">{{ $order->store->name }}</td>
                                <td class="py-4 px-6 text-sm font-black text-slate-900">Rp
                                    {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-[10px] font-black uppercase tracking-wider
                                                {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                                {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $order->status === 'shipped' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('admin.orders.show', $order) }}"
                                        class="inline-flex items-center rounded-xl bg-slate-900 px-4 py-2 text-xs font-black text-white hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 uppercase tracking-widest">
                                        Manage
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center text-slate-400 italic">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection