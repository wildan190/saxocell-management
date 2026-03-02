@extends('layouts.app')

@section('content')
    <div class="space-y-8 pb-24">
        {{-- Header --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('stores.show', $store) }}" class="text-slate-400 hover:text-slate-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Trade-In</h1>
                    <p class="text-slate-500 font-medium">{{ $store->name }}</p>
                </div>
            </div>
            <a href="{{ route('stores.trade-ins.create', $store) }}"
                class="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3 text-sm font-black text-white hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Pengajuan Baru
            </a>
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

        {{-- Stats --}}
        @php
            $pending = $tradeIns->where('status', 'pending')->count();
            $approved = $tradeIns->where('status', 'approved')->count();
            $rejected = $tradeIns->where('status', 'rejected')->count();
        @endphp
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-2xl p-5 ring-1 ring-slate-100 shadow-sm text-center">
                <p class="text-3xl font-black text-amber-500">{{ $pending }}</p>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">Menunggu</p>
            </div>
            <div class="bg-white rounded-2xl p-5 ring-1 ring-slate-100 shadow-sm text-center">
                <p class="text-3xl font-black text-green-600">{{ $approved }}</p>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">Disetujui</p>
            </div>
            <div class="bg-white rounded-2xl p-5 ring-1 ring-slate-100 shadow-sm text-center">
                <p class="text-3xl font-black text-red-500">{{ $rejected }}</p>
                <p class="text-xs font-black text-slate-400 uppercase tracking-widest mt-1">Ditolak</p>
            </div>
        </div>

        {{-- Table --}}
        <div class="bg-white rounded-[28px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            @if($tradeIns->isEmpty())
                <div class="py-24 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-slate-50 mb-4">
                        <svg class="w-10 h-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5" />
                        </svg>
                    </div>
                    <p class="text-xl font-black text-slate-400">Belum ada pengajuan trade-in</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-slate-100 bg-slate-50">
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">No.
                                    Trade-In</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Pelanggan</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Perangkat</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Kondisi</th>
                                <th class="text-right px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Nilai Taksir</th>
                                <th class="text-left px-6 py-4 text-xs font-black text-slate-400 uppercase tracking-widest">
                                    Status</th>
                                <th class="px-6 py-4"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($tradeIns as $ti)
                                @php
                                    $condColor = ['excellent' => 'text-green-700 bg-green-50', 'good' => 'text-blue-700 bg-blue-50', 'fair' => 'text-amber-700 bg-amber-50', 'poor' => 'text-red-700 bg-red-50'];
                                    $condLabel = ['excellent' => 'Sangat Baik', 'good' => 'Baik', 'fair' => 'Sedang', 'poor' => 'Rusak'];
                                    $statusColor = ['pending' => 'text-amber-700 bg-amber-50', 'approved' => 'text-green-700 bg-green-50', 'rejected' => 'text-red-700 bg-red-50'];
                                    $statusLabel = ['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'];
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-6 py-4 font-mono text-xs font-black text-slate-600">{{ $ti->trade_in_number }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-black text-slate-900">{{ $ti->customer_name }}</p>
                                        @if($ti->customer_contact)
                                        <p class="text-xs text-slate-400 font-medium">{{ $ti->customer_contact }}</p>@endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="font-bold text-slate-900">{{ $ti->device_name }}</p>
                                        @if($ti->brand)
                                        <p class="text-xs text-slate-400">{{ $ti->brand }} {{ $ti->model }}</p>@endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase {{ $condColor[$ti->condition] ?? 'bg-slate-100 text-slate-500' }}">
                                            {{ $condLabel[$ti->condition] ?? $ti->condition }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-slate-900">
                                        {{ $ti->assessed_value ? 'Rp ' . number_format($ti->assessed_value, 0, ',', '.') : '—' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex rounded-full px-3 py-1 text-[11px] font-black uppercase {{ $statusColor[$ti->status] ?? 'bg-slate-100 text-slate-500' }}">
                                            {{ $statusLabel[$ti->status] ?? $ti->status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('stores.trade-ins.show', [$store, $ti]) }}"
                                            class="text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors">Detail
                                            →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-100">{{ $tradeIns->links() }}</div>
            @endif
        </div>
    </div>
@endsection