@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <header>
            <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Dashboard Overviews</h1>
        </header>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Stat 1 -->
            <div
                class="overflow-hidden rounded-2xl bg-white px-4 py-6 shadow-sm border border-slate-100 transition-all hover:shadow-md">
                <dt class="truncate text-sm font-medium text-slate-500">Total Revenue</dt>
                <dd class="mt-1 text-3xl font-bold tracking-tight text-indigo-600">$405,091</dd>
            </div>
            <!-- Stat 2 -->
            <div
                class="overflow-hidden rounded-2xl bg-white px-4 py-6 shadow-sm border border-slate-100 transition-all hover:shadow-md">
                <dt class="truncate text-sm font-medium text-slate-500">Active Subscriptions</dt>
                <dd class="mt-1 text-3xl font-bold tracking-tight text-indigo-600">12,704</dd>
            </div>
            <!-- Stat 3 -->
            <div
                class="overflow-hidden rounded-2xl bg-white px-4 py-6 shadow-sm border border-slate-100 transition-all hover:shadow-md">
                <dt class="truncate text-sm font-medium text-slate-500">Avg. Click Rate</dt>
                <dd class="mt-1 text-3xl font-bold tracking-tight text-indigo-600">24.57%</dd>
            </div>
            <!-- Stat 4 -->
            <div
                class="overflow-hidden rounded-2xl bg-white px-4 py-6 shadow-sm border border-slate-100 transition-all hover:shadow-md">
                <dt class="truncate text-sm font-medium text-slate-500">Page Views</dt>
                <dd class="mt-1 text-3xl font-bold tracking-tight text-indigo-600">894,000</dd>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="rounded-2xl border border-slate-100 bg-white overflow-hidden shadow-sm">
            <div class="border-b border-slate-100 bg-slate-50/50 px-6 py-4">
                <h3 class="text-base font-semibold leading-6 text-slate-900">Recent Transactions</h3>
            </div>
            <div class="px-6 py-5">
                <div class="flow-root">
                    <ul role="list" class="-my-5 divide-y divide-slate-100">
                        @foreach(range(1, 5) as $i)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-slate-400" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 1.5v.75m0 1.5v.75m1.5-7.5h.75m1.5 0h.75m1.5 0h.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-900">Payment from Customer
                                            #{{ $i + 1000 }}</p>
                                        <p class="truncate text-xs text-slate-500">Processing via Stripe • 2 hours ago</p>
                                    </div>
                                    <div>
                                        <span
                                            class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-100">Success</span>
                                    </div>
                                    <div class="text-sm font-bold text-slate-900">
                                        +$1,200.00
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="mt-6">
                    <a href="#"
                        class="flex w-full items-center justify-center rounded-xl bg-slate-50 px-3 py-2 text-sm font-semibold text-slate-900 hover:bg-slate-100 transition-colors">View
                        all activity</a>
                </div>
            </div>
        </div>
    </div>
@endsection