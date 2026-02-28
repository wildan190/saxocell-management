@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header Area -->
        <div
            class="lg:flex lg:items-center lg:justify-between p-8 bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 border-l-4 border-amber-600">
            <div class="min-w-0 flex-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ route('warehouses.show', $warehouse) }}"
                                class="hover:text-slate-700">{{ $warehouse->name }}</a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">Incoming Requests</li>
                    </ol>
                </nav>
                <h2 class="text-3xl font-black leading-7 text-slate-900 sm:truncate sm:text-4xl sm:tracking-tight">
                    Incoming Requests
                </h2>
                <p class="mt-1 text-sm text-slate-500">Stock requests from various stores.</p>
            </div>
        </div>

        <!-- Requests List -->
        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100">
                    <thead class="bg-slate-50/50">
                        <tr>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Number
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Store
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status
                            </th>
                            <th class="py-4 px-6 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Requested At</th>
                            <th class="py-4 px-6 text-right text-xs font-bold text-slate-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($requests as $req)
                            <tr class="hover:bg-slate-50/30 transition-colors">
                                <td class="py-4 px-6 text-sm font-black text-slate-900">{{ $req->request_number }}</td>
                                <td class="py-4 px-6 text-sm text-slate-600 font-bold">{{ $req->store->name }}</td>
                                <td class="py-4 px-6">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-black uppercase tracking-wider
                                                {{ $req->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                                {{ $req->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                                {{ $req->status === 'shipped' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                                {{ $req->status === 'received' ? 'bg-green-100 text-green-700' : '' }}">
                                        {{ $req->status }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm text-slate-500 font-medium">
                                    {{ $req->created_at->format('M d, H:i') }}</td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('warehouses.incoming-requests.show', [$warehouse, $req]) }}"
                                        class="text-indigo-600 hover:text-indigo-500 font-black text-xs uppercase tracking-widest">Process</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <div class="h-12 w-12 text-slate-200 mb-4">
                                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-medium tracking-tight">No incoming requests.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection