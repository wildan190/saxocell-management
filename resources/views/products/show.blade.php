@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

    <div class="max-w-6xl mx-auto space-y-8 pb-24 px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-5">
                <a href="{{ route('products.index') }}"
                    class="group p-3 rounded-2xl bg-white shadow-sm border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 hover:shadow-md transition-all duration-300">
                    <svg class="h-6 w-6 transform group-hover:-translate-x-1 transition-transform" fill="none"
                        viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h1 class="text-4xl font-black tracking-tight text-slate-900">{{ $product->name }}</h1>
                        @php
                            $statusConfig = match ($product->status) {
                                'available' => ['class' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'dot' => 'bg-emerald-500'],
                                'service' => ['class' => 'bg-rose-50 text-rose-600 border-rose-100', 'dot' => 'bg-rose-500'],
                                'sold' => ['class' => 'bg-slate-50 text-slate-500 border-slate-200', 'dot' => 'bg-slate-400'],
                                default => ['class' => 'bg-slate-50 text-slate-500 border-slate-200', 'dot' => 'bg-slate-400'],
                            };
                        @endphp
                        <span
                            class="{{ $statusConfig['class'] }} flex items-center gap-2 text-[10px] font-black px-3 py-1.5 rounded-full uppercase tracking-widest border">
                            <span class="h-1.5 w-1.5 rounded-full {{ $statusConfig['dot'] }} animate-pulse"></span>
                            {{ $product->status_label }}
                        </span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span
                            class="text-xs font-black text-indigo-600/50 uppercase tracking-[0.2em]">{{ $product->sku }}</span>
                        @if($product->imei)
                            <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                            <span class="text-xs font-black text-slate-500 uppercase tracking-widest">IMEI: {{ $product->imei }}</span>
                        @endif
                        <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                        <span
                            class="text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $product->category }}</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" onclick="openActivityModal()"
                    class="flex items-center gap-2 rounded-2xl bg-slate-900 px-6 py-3.5 text-sm font-bold text-white shadow-xl shadow-slate-200 hover:bg-indigo-600 hover:shadow-indigo-100 transition-all active:scale-[0.98]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Record Activity
                </button>
                <button type="button" onclick="openPriceModal()"
                    class="p-3.5 rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-100 shadow-sm transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- 12-col Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

            <!-- ===== MAIN CONTENT: 8/12 ===== -->
            <div class="lg:col-span-8 space-y-8">

                <!-- Stats Row -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div
                        class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                        <p
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-indigo-600 transition-colors">
                            Stock Level</p>
                        <div class="flex items-baseline gap-1">
                            <span
                                class="text-3xl font-black text-slate-900">{{ number_format($product->inventories->sum('quantity'), 0, ',', '.') }}</span>
                            <span class="text-xs font-bold text-slate-400">{{ $product->unit }}</span>
                        </div>
                    </div>
                    <div
                        class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                        <p
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-rose-600 transition-colors">
                            Buy Price</p>
                        <p class="text-xl font-black text-slate-900">Rp
                            {{ number_format($product->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
                        <p
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 group-hover:text-emerald-600 transition-colors">
                            Sell Price</p>
                        <p class="text-xl font-black text-indigo-600">Rp
                            {{ number_format($product->selling_price, 0, ',', '.') }}</p>
                    </div>
                    <div
                        class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group text-center flex flex-col items-center justify-center">
                        <button onclick="printSingleQR()"
                            class="p-2 rounded-xl bg-indigo-50 text-indigo-600 hover:bg-indigo-100 transition-all mb-1">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                        </button>
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Print Label</p>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="h-8 w-1.5 rounded-full bg-indigo-600"></div>
                        <h2 class="text-xl font-bold text-slate-900">Product Synopsis</h2>
                    </div>
                    <p class="text-slate-600 leading-[1.8] text-lg font-medium">
                        {{ $product->description ?: 'No detailed description available for this item.' }}
                    </p>
                </div>

                <!-- Activity Log -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-10 border-b border-slate-50 flex items-center gap-3 bg-slate-50/30">
                        <div class="p-3 rounded-2xl bg-indigo-100 text-indigo-600">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-slate-900 leading-none mb-1">Activity Log</h2>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Lifecycle Ledger
                            </p>
                        </div>
                    </div>
                    <div class="p-10">
                        <div class="flow-root">
                            <ul role="list" class="-mb-10">
                                @forelse($product->activities as $activity)
                                    <li>
                                        <div class="relative pb-10">
                                            @if(!$loop->last)<span
                                                class="absolute left-6 top-10 -ml-px h-full w-[2px] bg-slate-100"
                                            aria-hidden="true"></span>@endif
                                            <div class="relative flex items-start space-x-6">
                                                <div class="relative mt-1">
                                                    @php
                                                        $typeConfig = match ($activity->activity_type) {
                                                            'repair' => ['bg' => 'bg-rose-50 text-rose-600 border-rose-100', 'icon' => 'M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.83-5.83m-5.83 5.83a2.652 2.652 0 11-3.75-3.75l5.83-5.83m0 0l-5.83-5.83m5.83 5.83l5.83-5.83'],
                                                            'improvement' => ['bg' => 'bg-indigo-50 text-indigo-600 border-indigo-100', 'icon' => 'M2.25 18L9 11.25l4.5 4.5L21.75 7.5'],
                                                            'price_adjustment' => ['bg' => 'bg-amber-50 text-amber-600 border-amber-100', 'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                                            'status_change' => ['bg' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5'],
                                                            default => ['bg' => 'bg-slate-50 text-slate-500 border-slate-200', 'icon' => 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008h-.008V12.75z'],
                                                        };
                                                    @endphp
                                                    <div
                                                        class="flex h-12 w-12 items-center justify-center rounded-[1.25rem] {{ $typeConfig['bg'] }} border shadow-sm ring-8 ring-white hover:scale-110 transition-transform duration-300">
                                                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="{{ $typeConfig['icon'] }}" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div class="min-w-0 flex-1 py-1">
                                                    <div class="flex justify-between items-start gap-4">
                                                        <div>
                                                            <div class="flex items-center gap-3">
                                                                <p class="text-base font-black text-slate-900">
                                                                    {{ $activity->activity_type_label }}</p>
                                                                <span
                                                                    class="text-[9px] font-black px-2 py-0.5 rounded-full bg-slate-100 text-slate-400 uppercase tracking-widest border border-slate-200">#{{ str_pad($activity->id, 5, '0', STR_PAD_LEFT) }}</span>
                                                            </div>
                                                            <p class="mt-1.5 text-sm text-slate-500 font-medium leading-[1.6]">
                                                                {{ $activity->description }}</p>
                                                        </div>
                                                        <div class="text-right shrink-0">
                                                            <p
                                                                class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">
                                                                {{ $activity->created_at->format('d M Y') }}</p>
                                                            <p
                                                                class="text-[9px] font-bold text-slate-300 uppercase leading-none">
                                                                {{ $activity->created_at->format('H:i') }}</p>
                                                        </div>
                                                    </div>

                                                    @if($activity->cost > 0)
                                                        <div class="mt-4 flex items-center gap-3">
                                                            <span
                                                                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-950 px-3 py-1.5 text-[10px] font-black text-white uppercase tracking-wider">
                                                                <span class="h-1 w-1 rounded-full bg-rose-500 animate-pulse"></span>
                                                                Expense: Rp {{ number_format($activity->cost, 0, ',', '.') }}
                                                            </span>
                                                            @if($activity->finance_transaction_id)
                                                                <span
                                                                    class="text-[9px] font-black text-emerald-500 uppercase tracking-tighter">✓
                                                                    Finance Cleared</span>
                                                            @endif
                                                        </div>
                                                    @endif

                                                    @if($activity->activity_type === 'price_adjustment')
                                                        <div
                                                            class="mt-4 grid grid-cols-2 gap-4 p-5 bg-indigo-50/30 rounded-[1.5rem] border border-indigo-100/50">
                                                            <div>
                                                                <p
                                                                    class="text-[9px] font-black text-slate-400 uppercase tracking-[0.1em] mb-2 leading-none">
                                                                    Acquisition Cost</p>
                                                                <div class="flex items-baseline gap-2">
                                                                    <p class="text-sm font-black text-slate-800">Rp
                                                                        {{ number_format($activity->new_purchase_price, 0, ',', '.') }}
                                                                    </p>
                                                                    <p class="text-[10px] font-bold text-slate-300 line-through">Rp
                                                                        {{ number_format($activity->old_purchase_price, 0, ',', '.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                            <div class="border-l border-indigo-100 pl-6">
                                                                <p
                                                                    class="text-[9px] font-black text-indigo-400 uppercase tracking-[0.1em] mb-2 leading-none">
                                                                    Market Val. (Sell)</p>
                                                                <div class="flex items-baseline gap-2">
                                                                    <p class="text-sm font-black text-indigo-600">Rp
                                                                        {{ number_format($activity->new_selling_price, 0, ',', '.') }}
                                                                    </p>
                                                                    <p class="text-[10px] font-bold text-slate-300 line-through">Rp
                                                                        {{ number_format($activity->old_selling_price, 0, ',', '.') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    <div class="mt-4 flex items-center pt-4 border-t border-slate-50">
                                                        <div class="flex items-center gap-2">
                                                            <div
                                                                class="h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-[8px] font-black text-slate-500 border border-slate-200 uppercase">
                                                                {{ substr($activity->user->name ?? 'S', 0, 1) }}
                                                            </div>
                                                            <span class="text-[10px] font-bold text-slate-400">Reporter: <span
                                                                    class="text-slate-600 uppercase">{{ $activity->user->name ?? 'System' }}</span></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <div class="py-20 text-center space-y-4">
                                        <div
                                            class="h-16 w-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto">
                                            <svg class="w-8 h-8 text-slate-200" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                            </svg>
                                        </div>
                                        <p class="text-slate-400 font-bold tracking-widest uppercase text-xs">Awaiting First
                                            Entry</p>
                                    </div>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Quality Label Card -->
                @php $qlc = $product->quality_label_config; @endphp
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-10 border-b border-slate-50 flex items-center justify-between bg-slate-50/30">
                        <div class="flex items-center gap-3">
                            <div
                                class="p-3 rounded-2xl {{ $qlc['color'] === 'none' ? 'bg-emerald-100 text-emerald-600' : ($qlc['color'] === 'yellow' ? 'bg-yellow-100 text-yellow-600' : 'bg-rose-100 text-rose-600') }}">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-900 leading-none mb-1">Quality Label</h2>
                                <span
                                    class="{{ $qlc['class'] }} text-[10px] font-black px-3 py-1 rounded-full border uppercase tracking-widest">{{ $qlc['label'] }}</span>
                            </div>
                        </div>
                        <button onclick="openLabelModal()"
                            class="rounded-2xl bg-slate-100 px-5 py-2.5 text-xs font-black text-slate-600 hover:bg-slate-200 uppercase tracking-widest transition-all">
                            Ubah Label
                        </button>
                    </div>

                    @if($product->quality_description)
                        <div class="px-10 pt-8 pb-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Keterangan</p>
                            <p class="text-slate-600 font-medium leading-relaxed">{{ $product->quality_description }}</p>
                        </div>
                    @endif

                    <div class="px-10 pb-10 pt-6">
                        @if($product->quality_label === 'none')
                            <div class="flex items-center gap-3 p-4 bg-emerald-50 rounded-2xl border border-emerald-100">
                                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                                <p class="text-xs font-bold text-emerald-700">Barang sesuai — dapat dipindahkan ke store kapan
                                    saja.</p>
                            </div>
                        @elseif($product->quality_label === 'yellow')
                            <div class="p-5 bg-yellow-50 rounded-2xl border border-yellow-100 space-y-3">
                                <p class="text-[10px] font-black text-yellow-700 uppercase tracking-widest">Aksi Tersedia:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1.5 bg-white text-yellow-700 border border-yellow-200 rounded-xl text-xs font-bold">1.
                                        Bisa dijual (kirim ke store)</span>
                                    <button onclick="openCompensationModal()"
                                        class="px-3 py-1.5 bg-yellow-600 text-white rounded-xl text-xs font-bold hover:bg-yellow-700 transition-all">2.
                                        Adjustment harga kompensasi</button>
                                    <button onclick="openLabelModal()"
                                        class="px-3 py-1.5 bg-white text-yellow-700 border border-yellow-200 rounded-xl text-xs font-bold hover:bg-yellow-50 transition-all">3.
                                        Ubah label</button>
                                </div>
                            </div>
                        @elseif($product->quality_label === 'red')
                            <div class="p-5 bg-rose-50 rounded-2xl border border-rose-100 space-y-3">
                                <p class="text-[10px] font-black text-rose-700 uppercase tracking-widest">Aksi Tersedia:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span
                                        class="px-3 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-xs font-bold line-through">1.
                                        Tidak bisa dijual ke store</span>
                                    <button onclick="openReturnModal()"
                                        class="px-3 py-1.5 bg-rose-600 text-white rounded-xl text-xs font-bold hover:bg-rose-700 transition-all">2.
                                        Ajukan Retur ke Supplier</button>
                                    <button onclick="openLabelModal()"
                                        class="px-3 py-1.5 bg-white text-rose-700 border border-rose-200 rounded-xl text-xs font-bold hover:bg-rose-50 transition-all">3.
                                        Ubah label</button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Return History -->
                @if($product->productReturns->isNotEmpty())
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-rose-100 overflow-hidden">
                        <div class="p-10 border-b border-rose-50 bg-rose-50/20">
                            <h2 class="text-xl font-bold text-slate-900 leading-none mb-1">Histori Retur</h2>
                            <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Return Ledger</p>
                        </div>
                        <div class="divide-y divide-slate-50">
                            @foreach($product->productReturns as $ret)
                                @php
                                    $retTotal = $ret->payments->sum('amount');
                                    $selisih = $product->purchase_price - $retTotal;
                                    $statusColor = match ($ret->status) {
                                        'shipped' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                                        'arrived' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'cleared' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        default => 'bg-slate-50 text-slate-500 border-slate-200',
                                    };
                                @endphp
                                <div class="p-8 space-y-4">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <div class="flex items-center gap-2 mb-1">
                                                <span
                                                    class="{{ $statusColor }} text-[10px] font-black px-2.5 py-1 rounded-full border uppercase tracking-widest">{{ $ret->status_label }}</span>
                                                @if($ret->resi)<span class="text-xs font-mono font-bold text-slate-400">Resi:
                                                {{ $ret->resi }}</span>@endif
                                            </div>
                                            <p class="text-xs text-slate-500">Dibuat oleh <strong>{{ $ret->user->name }}</strong> ·
                                                {{ $ret->created_at->format('d M Y') }}</p>
                                            @if($ret->notes)
                                            <p class="text-xs text-slate-400 italic mt-1">{{ $ret->notes }}</p>@endif
                                        </div>
                                        <div class="text-right">
                                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Ongkir
                                            </p>
                                            <p class="text-sm font-black text-slate-800">
                                                {{ $ret->shipping_cost > 0 ? 'Rp ' . number_format($ret->shipping_cost, 0, ',', '.') : '-' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($ret->payments->isNotEmpty())
                                        <div class="bg-slate-50 rounded-2xl p-4 space-y-2">
                                            @foreach($ret->payments as $pay)
                                                <div class="flex justify-between items-center">
                                                    <span class="text-xs text-slate-500">{{ $pay->payment_date->format('d M Y') }} —
                                                        {{ $pay->financeAccount->name ?? '-' }}</span>
                                                    <span class="text-xs font-black text-emerald-600">+Rp
                                                        {{ number_format($pay->amount, 0, ',', '.') }}</span>
                                                </div>
                                            @endforeach
                                            <div class="pt-2 mt-2 border-t border-slate-200 flex justify-between items-center">
                                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total
                                                    Kembali</span>
                                                <span class="text-sm font-black text-slate-900">Rp
                                                    {{ number_format($retTotal, 0, ',', '.') }}</span>
                                            </div>
                                            @if($ret->status !== 'cleared')
                                                <div class="flex justify-between items-center">
                                                    <span class="text-[10px] font-black text-rose-400 uppercase tracking-widest">Selisih
                                                        (HPP)</span>
                                                    <span
                                                        class="text-sm font-black {{ $selisih > 0 ? 'text-rose-600' : 'text-emerald-600' }}">Rp
                                                        {{ number_format(abs($selisih), 0, ',', '.') }}
                                                        {{ $selisih > 0 ? '(rugi)' : '(untung)' }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap gap-2">
                                        @if($ret->status === 'shipped')
                                            <form action="{{ route('products.returns.arrive', $ret) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded-xl text-xs font-black hover:bg-blue-700 transition-all">✓
                                                    Sudah Sampai</button>
                                            </form>
                                        @endif
                                        @if(in_array($ret->status, ['arrived', 'shipped']))
                                            <button onclick="openPaymentModal({{ $ret->id }})"
                                                class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-black hover:bg-emerald-700 transition-all">+
                                                Input Nominal Kembali</button>
                                            <form action="{{ route('products.returns.clear', $ret) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="px-4 py-2 bg-slate-900 text-white rounded-xl text-xs font-black hover:bg-slate-700 transition-all"
                                                    onclick="return confirm('Tandai retur sebagai selesai?')">✓ Clear</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>{{-- end lg:col-span-8 --}}

            <!-- ===== SIDEBAR: 4/12 ===== -->
            <div class="lg:col-span-4 space-y-8 lg:sticky lg:top-8">

                <!-- Store Prices Card -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/20">
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Store Selling Prices</h2>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($product->storeProducts as $sp)
                            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 leading-none mb-1">{{ $sp->store->name }}
                                        </p>
                                        <p
                                            class="text-[10px] font-bold {{ $sp->is_active ? 'text-emerald-500' : 'text-rose-400' }} uppercase">
                                            {{ $sp->is_active ? 'Active' : 'Inactive' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-base font-black text-indigo-600 leading-none mb-1">Rp
                                        {{ number_format($sp->price, 0, ',', '.') }}</p>
                                    <p class="text-[9px] font-bold text-slate-300 uppercase">Per {{ $product->unit }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Not Assigned to
                                    Stores</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- QR Card -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-10 text-center group">
                    <div
                        class="inline-flex p-5 rounded-3xl bg-indigo-50/50 border border-indigo-100 mb-6 group-hover:scale-105 transition-transform duration-500">
                        <div id="product-qr"></div>
                    </div>
                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-2 leading-none">Digital SKU
                        Identity</h3>
                    <p class="text-[10px] font-bold text-slate-300 uppercase mb-8">{{ $product->sku }}</p>
                    <button type="button" onclick="printSingleQR()"
                        class="w-full flex items-center justify-center gap-3 rounded-[1.5rem] bg-indigo-50 px-6 py-4 text-xs font-black text-indigo-600 border border-indigo-100/50 hover:bg-indigo-600 hover:text-white transition-all duration-300">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Deploy Physical Label
                    </button>
                </div>

                <!-- Inventory Distribution -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50 bg-slate-50/20">
                        <h2 class="text-sm font-black text-slate-900 uppercase tracking-widest">Inventory Distribution</h2>
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($product->inventories as $inv)
                            <div class="p-6 flex items-center justify-between hover:bg-slate-50 transition-colors">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 leading-none mb-1">
                                            {{ $inv->warehouse->name }}</p>
                                        <p class="text-[9px] font-bold text-slate-400 uppercase">
                                            {{ $inv->warehouse->address ?? 'Primary Site' }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-black text-indigo-600 leading-none mb-1">
                                        {{ number_format($inv->quantity, 0, ',', '.') }}</p>
                                    <p class="text-[9px] font-black text-slate-300 uppercase">{{ $product->unit }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="p-10 text-center">
                                <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">No Active Stock</p>
                            </div>
                        @endforelse
                    </div>
                </div>

            </div>{{-- end lg:col-span-4 --}}

        </div>{{-- end grid --}}
    </div>{{-- end max-w-6xl --}}

    @include('products.partials.modals')

    <script>
                   function openPriceModal()        { document.getElementById('price-modal').classList.remove('hidden');        document.body.style.overflow='hidden'; }
            function closePriceModal()       { document.getElementById('price-modal').classList.add('hidden');           document.body.style.overflow='auto'; }
            function openActivityModal()     { document.getElementById('activity-modal').classList.remove('hidden');     document.body.style.overflow='hidden'; }
            function closeActivityModal()    { document.getElementById('activity-modal').classList.add('hidden');        document.body.style.overflow='auto'; }
            function openLabelModal()        { document.getElementById('label-modal').classList.remove('hidden');        document.body.style.overflow='hidden'; }
            function closeLabelModal()       { document.getElementById('label-modal').classList.add('hidden');           document.body.style.overflow='auto'; }
            function openReturnModal()       { document.getElementById('return-modal').classList.remove('hidden');       document.body.style.overflow='hidden'; }
            function closeReturnModal()      { document.getElementById('return-modal').classList.add('hidden');          document.body.style.overflow='auto'; }
            function openCompensationModal() { document.getElementById('compensation-modal').classList.remove('hidden'); document.body.style.overflow='hidden'; }
            function closeCompensationModal(){ document.getElementById('compensation-modal').classList.add('hidden');    document.body.style.overflow='auto'; }
            function openPaymentModal(returnId) {
                document.getElementById('payment-form').action = '/products/returns/' + returnId + '/payment';
                document.getElementById('payment-modal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            function closePaymentModal() { document.getElementById('payment-modal').classList.add('hidden'); document.body.style.overflow='auto'; }

            document.addEventListener("DOMContentLoaded", function () {
                const container = document.getElementById("product-qr");
                if (container) {
                    container.innerHTML = "";
                    new QRCode(container, {
                        text: "{{ $product->sku }}",
                        width: 140, height: 140,
                        colorDark: "#4338ca", colorLight: "#ffffff",
                        correctLevel: QRCode.Level ? QRCode.Level.H : 2
                    });
                }
            });

            function printSingleQR() {
                const sku = "{{ $product->sku }}";
                const imei = "{{ $product->imei || $product->sku }}";
                const name = "{{ addslashes($product->name) }}";
                const price = "Rp {{ number_format($product->selling_price, 0, ',', '.') }}";
                const qrCanvas = document.querySelector('#product-qr canvas');
                if (!qrCanvas) return;
                const qrImage = qrCanvas.toDataURL("image/png");
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`<html><head><title>Print Label - ${sku}</title><style>@page{size:50mm 40mm;margin:0}body{font-family:system-ui;margin:0;padding:2mm;text-align:center;display:flex;flex-direction:column;align-items:center;justify-content:center;height:36mm}.sku{font-size:10pt;font-weight:bold;margin-top:2mm}.imei{font-size:8pt;font-weight:bold;color:#666;margin-top:1mm}.name{font-size:8pt;color:#444;margin-top:1mm}img{max-width:25mm;height:auto}</style></head><body><img src="${qrImage}"/><div class="sku">${sku}</div><div class="imei">IMEI: ${imei}</div><div class="name">${name}</div><div class="price">${price}</div><script>window.onload=()=>{window.print();window.close()}<\/script></body></html>`);
                printWindow.document.close();
            }
        </script>
@endsection