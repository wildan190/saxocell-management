@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <!-- Header -->
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li>
                            @if($owner instanceof \App\Models\Warehouse)
                                <a href="{{ route('warehouses.show', ['warehouse' => $owner->id]) }}"
                                    class="hover:text-slate-700">
                                    {{ $owner->name }}
                                </a>
                            @else
                                <a href="{{ route('stores.show', ['store' => $owner->id]) }}" class="hover:text-slate-700">
                                    {{ $owner->name }}
                                </a>
                            @endif
                        </li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">Mutasi Akun</li>
                    </ol>
                </nav>
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900 font-display">
                            {{ $account->name }}
                        </h1>
                        <p class="text-sm text-slate-500 font-medium">Mutasi Transaksi & Riwayat Saldo</p>
                    </div>
                </div>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 flex gap-3">
                @if($owner instanceof \App\Models\Warehouse)
                    <a href="{{ route('finance.transactions.index', ['warehouse' => $owner->id]) }}"
                        class="block rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-center text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all active:scale-[0.98]">
                        Semua Transaksi
                    </a>
                @else
                    <a href="{{ route('stores.finance.transactions.index', ['store' => $owner->id]) }}"
                        class="block rounded-xl bg-white border border-slate-200 px-4 py-2.5 text-center text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all active:scale-[0.98]">
                        Semua Transaksi
                    </a>
                @endif
            </div>
        </div>

        <!-- Account Info & Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div
                class="md:col-span-1 bg-white p-8 rounded-3xl shadow-sm ring-1 ring-slate-100 border-b-4 border-indigo-600">
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Saldo Saat Ini</p>
                <h2 class="text-4xl font-black text-slate-900">Rp {{ number_format($account->balance, 2, ',', '.') }}</h2>
                <div class="mt-4 flex items-center gap-2">
                    <span
                        class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-xs font-bold text-indigo-700 uppercase tracking-tighter">
                        {{ $account->type }}
                    </span>
                </div>
            </div>

            <div class="md:col-span-2 grid grid-cols-2 gap-6 leading-none">
                <div class="bg-emerald-50/50 p-6 rounded-3xl border border-emerald-100">
                    <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2">Total Pemasukan</p>
                    <p class="text-2xl font-black text-emerald-700">Rp
                        {{ number_format($transactions->where('type', 'income')->sum('amount'), 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-rose-50/50 p-6 rounded-3xl border border-rose-100">
                    <p class="text-[10px] font-black text-rose-600 uppercase tracking-widest mb-2">Total Pengeluaran</p>
                    <p class="text-2xl font-black text-rose-700">Rp
                        {{ number_format($transactions->where('type', 'expense')->sum('amount'), 2, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Mutation Table -->
        <div class="bg-white shadow-sm ring-1 ring-slate-100 rounded-3xl overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900">Histori Mutasi</h3>
            </div>
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-white">
                    <tr>
                        <th
                            class="py-4 pl-8 pr-3 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Waktu</th>
                        <th class="px-3 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Deskripsi</th>
                        <th class="px-3 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Kategori</th>
                        <th class="px-3 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-8 pr-3">
                                <span
                                    class="text-xs font-bold text-slate-900">{{ $tx->transaction_date->format('d M Y') }}</span>
                            </td>
                            <td class="px-3 py-4">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-sm font-bold text-slate-900">{{ $tx->title }}</span>
                                    @if($tx->notes)
                                        <span class="text-[10px] text-slate-400 italic">{{ Str::limit($tx->notes, 50) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4">
                                <span
                                    class="inline-flex items-center rounded-lg bg-slate-100 px-2 py-0.5 text-[10px] font-bold text-slate-600 uppercase">
                                    {{ $tx->category }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-right pr-8">
                                <span
                                    class="text-sm font-black {{ $tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $tx->type === 'income' ? '+' : '-' }}
                                    Rp {{ number_format($tx->amount, 2, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="h-12 w-12 rounded-full bg-slate-50 flex items-center justify-center mb-3">
                                        <svg class="w-6 h-6 text-slate-300" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <p class="text-sm text-slate-400 italic text-center">Belum ada mutasi transaksi pada akun
                                        ini.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            @if($transactions->hasPages())
                <div class="px-8 py-4 border-t border-slate-50">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection