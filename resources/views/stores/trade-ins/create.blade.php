@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto space-y-8 pb-24">
        <div class="flex items-center gap-3">
            <a href="{{ route('stores.trade-ins.index', $store) }}"
                class="text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Pengajuan Trade-In Baru</h1>
                <p class="text-slate-500 text-sm font-medium">{{ $store->name }}</p>
            </div>
        </div>

        @if($errors->any())
            <div class="rounded-2xl bg-red-50 border border-red-200 px-6 py-4 text-red-800 font-bold text-sm">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stores.trade-ins.store', $store) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-[28px] ring-1 ring-slate-100 shadow-sm p-8 space-y-6">
                <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3">Data
                    Pelanggan</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nama Pelanggan
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('customer_name') border-red-400 @enderror">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">No.
                            Kontak</label>
                        <input type="text" name="customer_contact" value="{{ old('customer_contact') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Ditangani
                            Oleh</label>
                        <input type="text" name="handled_by" value="{{ old('handled_by') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[28px] ring-1 ring-slate-100 shadow-sm p-8 space-y-6">
                <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest border-b border-slate-100 pb-3">
                    Detail Perangkat</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nama Perangkat
                            <span class="text-red-500">*</span></label>
                        <input type="text" name="device_name" value="{{ old('device_name') }}" required
                            placeholder="e.g. iPhone 14 Pro"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('device_name') border-red-400 @enderror">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Merk</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" placeholder="e.g. Apple"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Model</label>
                        <input type="text" name="model" value="{{ old('model') }}" placeholder="e.g. A2892"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">IMEI /
                            Serial</label>
                        <input type="text" name="imei" value="{{ old('imei') }}"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 font-mono">
                    </div>
                    <div>
                        <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Nilai Taksir
                            (Rp)</label>
                        <input type="number" name="assessed_value" value="{{ old('assessed_value') }}" min="0"
                            placeholder="0"
                            class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                {{-- Condition --}}
                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Kondisi Perangkat
                        <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['excellent' => ['Sangat Baik', 'green'], 'good' => ['Baik', 'blue'], 'fair' => ['Sedang', 'amber'], 'poor' => ['Rusak', 'red']] as $val => [$label, $color])
                            <label class="relative cursor-pointer">
                                <input type="radio" name="condition" value="{{ $val }}" {{ old('condition', 'good') === $val ? 'checked' : '' }} class="peer sr-only">
                                <div
                                    class="rounded-2xl border-2 border-slate-200 p-4 text-center peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-50 transition-all hover:border-slate-300">
                                    <p class="font-black text-sm peer-checked:text-{{ $color }}-700 text-slate-600">{{ $label }}
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-black text-slate-500 uppercase tracking-widest mb-2">Catatan
                        Kondisi</label>
                    <textarea name="condition_notes" rows="3" placeholder="Describe any damage, accessories included, etc."
                        class="w-full rounded-2xl border border-slate-200 px-4 py-3 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none">{{ old('condition_notes') }}</textarea>
                </div>
            </div>

            <div class="flex gap-3">
                <a href="{{ route('stores.trade-ins.index', $store) }}"
                    class="flex-1 text-center rounded-2xl border border-slate-200 py-4 text-sm font-black text-slate-600 hover:bg-slate-50 transition-all">
                    Batal
                </a>
                <button type="submit"
                    class="flex-1 rounded-2xl bg-slate-900 py-4 text-sm font-black text-white hover:bg-indigo-600 transition-all active:scale-[0.98] shadow-xl">
                    Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
@endsection