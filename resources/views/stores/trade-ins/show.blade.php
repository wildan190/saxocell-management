@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 pb-24">
        {{-- Header --}}
        <div class="flex items-center gap-3">
            <a href="{{ route('stores.trade-ins.index', $store) }}"
                class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Detail Trade-In</h1>
                <p class="text-slate-500 text-sm font-medium">{{ $store->name }}</p>
            </div>
        </div>

        @if(session('success'))
            <div
                class="rounded-2xl bg-green-50 border border-green-200 px-6 py-4 text-green-800 font-bold text-sm flex items-center gap-3">
                <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @php
            $statusColor = ['pending' => 'amber', 'approved' => 'green', 'rejected' => 'red'];
            $statusLabel = ['pending' => 'Menunggu Penilaian', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'];
            $c = $statusColor[$tradeIn->status] ?? 'slate';
            $condColor = ['excellent' => 'green', 'good' => 'blue', 'fair' => 'amber', 'poor' => 'red'];
            $condLabel = ['excellent' => 'Sangat Baik', 'good' => 'Baik', 'fair' => 'Sedang', 'poor' => 'Rusak'];
            $cc = $condColor[$tradeIn->condition] ?? 'slate';
        @endphp

        {{-- Status Banner --}}
        <div class="bg-{{ $c }}-50 border border-{{ $c }}-200 rounded-2xl px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <span class="w-3 h-3 rounded-full bg-{{ $c }}-500 flex-shrink-0"></span>
                <span
                    class="font-black text-{{ $c }}-800 text-sm">{{ $statusLabel[$tradeIn->status] ?? ucfirst($tradeIn->status) }}</span>
            </div>
            <span class="font-mono text-xs text-{{ $c }}-600 font-black">{{ $tradeIn->trade_in_number }}</span>
        </div>

        {{-- Detail Card --}}
        <div class="bg-white rounded-[28px] ring-1 ring-slate-100 shadow-sm p-8 space-y-6">
            <div class="grid grid-cols-2 gap-x-8 gap-y-5 text-sm">
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Pelanggan</p>
                    <p class="font-black text-slate-900">{{ $tradeIn->customer_name }}</p>
                    @if($tradeIn->customer_contact)
                    <p class="text-slate-500 font-medium">{{ $tradeIn->customer_contact }}</p>@endif
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Ditangani</p>
                    <p class="font-bold text-slate-700">{{ $tradeIn->handled_by ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Perangkat</p>
                    <p class="font-black text-slate-900">{{ $tradeIn->device_name }}</p>
                    @if($tradeIn->brand || $tradeIn->model)
                    <p class="text-slate-500 font-medium text-xs">{{ $tradeIn->brand }} {{ $tradeIn->model }}</p>@endif
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">IMEI / Serial</p>
                    <p class="font-mono font-bold text-slate-700 text-xs">{{ $tradeIn->imei ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Kondisi</p>
                    <span class="inline-flex rounded-full bg-{{ $cc }}-50 text-{{ $cc }}-700 px-3 py-1 text-xs font-black">
                        {{ $condLabel[$tradeIn->condition] ?? ucfirst($tradeIn->condition) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Nilai Taksir</p>
                    <p class="font-black text-slate-900">
                        {{ $tradeIn->assessed_value ? 'Rp ' . number_format($tradeIn->assessed_value, 0, ',', '.') : '—' }}
                    </p>
                </div>
                @if($tradeIn->status === 'approved')
                    <div>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Harga Beli</p>
                        <p class="font-black text-green-700 text-lg">Rp
                            {{ number_format($tradeIn->purchase_price, 0, ',', '.') }}</p>
                    </div>
                @endif
                @if($tradeIn->status === 'rejected' && $tradeIn->rejection_reason)
                    <div class="col-span-2">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Alasan Penolakan</p>
                        <p class="font-medium text-red-700">{{ $tradeIn->rejection_reason }}</p>
                    </div>
                @endif
                <div class="col-span-2">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Pengajuan</p>
                    <p class="font-bold text-slate-700">{{ $tradeIn->created_at->format('d M Y H:i') }} WIB</p>
                </div>
            </div>

            @if($tradeIn->condition_notes)
                <div class="bg-slate-50 rounded-2xl px-4 py-4">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Catatan Kondisi</p>
                    <p class="text-sm font-medium text-slate-700">{{ $tradeIn->condition_notes }}</p>
                </div>
            @endif
        </div>

        {{-- Actions for Pending --}}
        @if($tradeIn->status === 'pending')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Approve --}}
                <div class="bg-green-50 border border-green-200 rounded-[24px] p-6 space-y-4">
                    <div class="flex items-center gap-2 text-green-700">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-black text-sm">Setujui Trade-In</span>
                    </div>
                    <form action="{{ route('stores.trade-ins.approve', [$store, $tradeIn]) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-green-700 mb-1">Harga Beli (Rp) *</label>
                            <input type="number" name="purchase_price" required min="0" placeholder="0"
                                class="w-full rounded-xl border border-green-200 bg-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-green-700 mb-1">Ditangani Oleh</label>
                            <input type="text" name="handled_by" placeholder="Nama staff"
                                class="w-full rounded-xl border border-green-200 bg-white px-3 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-green-700 mb-1">Catat Pengeluaran ke Akun *</label>
                            @if($accounts->isEmpty())
                                <div class="rounded-xl bg-amber-50 border border-amber-200 px-3 py-2 text-xs font-bold text-amber-700">
                                    ⚠ Belum ada akun keuangan.
                                </div>
                            @else
                                <select name="finance_account_id" required
                                    class="w-full rounded-xl border border-green-200 bg-white px-3 py-2.5 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-green-500">
                                    @foreach($accounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} — Rp {{ number_format($acc->balance, 0, ',', '.') }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <button type="submit"
                            class="w-full rounded-xl bg-green-600 text-white py-3 text-sm font-black hover:bg-green-700 transition-all active:scale-95">
                            Setujui
                        </button>
                    </form>
                </div>

                {{-- Reject --}}
                <div class="bg-red-50 border border-red-200 rounded-[24px] p-6 space-y-4">
                    <div class="flex items-center gap-2 text-red-600">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        <span class="font-black text-sm">Tolak Trade-In</span>
                    </div>
                    <form action="{{ route('stores.trade-ins.reject', [$store, $tradeIn]) }}" method="POST" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-red-600 mb-1">Alasan Penolakan</label>
                            <textarea name="rejection_reason" rows="3" placeholder="Opsional..."
                                class="w-full rounded-xl border border-red-200 bg-white px-3 py-2.5 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-500 resize-none"></textarea>
                        </div>
                        <button type="submit" onclick="return confirm('Yakin ingin menolak trade-in ini?')"
                            class="w-full rounded-xl bg-red-600 text-white py-3 text-sm font-black hover:bg-red-700 transition-all active:scale-95">
                            Tolak
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection