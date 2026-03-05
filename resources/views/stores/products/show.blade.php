@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-8 pb-20">
        <!-- Breadcrumbs & Header -->
        <div class="sm:flex sm:items-center justify-between">
            <div class="space-y-1">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-xs font-bold text-slate-400 uppercase tracking-widest">
                        <li><a href="{{ route('stores.show', $store) }}"
                                class="hover:text-slate-600 transition-colors">Store</a></li>
                        <li><svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li><a href="{{ route('stores.products.index', $store) }}"
                                class="hover:text-slate-600 transition-colors">Manage Products</a></li>
                        <li><svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="text-slate-900">{{ $product->product->sku }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-black tracking-tight text-slate-900">{{ $product->product->name }}</h1>
            </div>
            <div class="flex gap-3">
                <button type="button"
                    onclick="openAdjustModal({{ $product->id }}, '{{ addslashes($product->product->name) }}', '{{ $product->description_1 }}', '{{ $product->description_2 }}', '{{ $product->description_3 }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->is_active ? 'true' : 'false' }})"
                    class="rounded-xl bg-slate-900 px-6 py-2.5 text-sm font-bold text-white shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all active:scale-[0.98]">
                    Edit Details
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Image & Basic Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Product Card -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden group">
                    <div class="aspect-square bg-slate-50 relative overflow-hidden">
                        @if($product->image_path)
                            <img src="{{ asset('storage/' . $product->image_path) }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300">
                                <svg class="w-20 h-20 mb-2 opacity-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                </svg>
                                <span class="text-xs font-bold uppercase tracking-widest opacity-50">No Product Image</span>
                            </div>
                        @endif

                        <!-- Status Badge -->
                        <div class="absolute top-4 right-4">
                            <span
                                class="inline-flex items-center rounded-full px-4 py-1 text-xs font-black uppercase tracking-wider {{ $product->is_active ? 'bg-green-500/90 text-white backdrop-blur-md' : 'bg-slate-500/90 text-white backdrop-blur-md' }}">
                                {{ $product->is_active ? 'Live' : 'Hidden' }}
                            </span>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-50/50">
                        <div class="flex justify-between items-end mb-6">
                            <div class="space-y-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Base SKU</p>
                                <p
                                    class="text-sm font-mono font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-lg inline-block">
                                    {{ $product->product->sku }}
                                </p>
                            </div>
                            <div class="text-right space-y-1">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category</p>
                                <p class="text-sm font-bold text-slate-900">
                                    {{ $product->product->category ?? 'Uncategorized' }}
                                </p>
                            </div>
                        </div>

                        <div
                            class="p-6 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-between">
                            <div class="space-y-0.5">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Store Price</p>
                                <p class="text-2xl font-black text-slate-900">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}
                                </p>
                            </div>
                            <svg class="h-8 w-8 text-slate-100" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 2.18l7 3.12v4.7c0 4.67-3.13 8.94-7 10.18-3.87-1.24-7-5.51-7-10.18V6.3l7-3.12z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Stock Summary -->
                <div
                    class="bg-indigo-600 rounded-[2rem] p-8 text-white shadow-xl shadow-indigo-100 relative overflow-hidden group">
                    <div class="relative z-10 flex items-center justify-between">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest">Current Stock</p>
                            <p class="text-4xl font-black">{{ $product->stock }} <span
                                    class="text-lg font-medium opacity-70">{{ $product->product->unit }}</span></p>
                        </div>
                        <div class="h-16 w-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-md">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-6 pt-6 border-t border-white/10 relative z-10">
                        <div
                            class="flex justify-between items-center text-xs font-bold uppercase tracking-widest text-indigo-200">
                            <span>Inventory Status</span>
                            @if($product->stock <= 5)
                                <span class="text-rose-300 flex items-center gap-1">
                                    <span class="h-1.5 w-1.5 rounded-full bg-rose-400 animate-pulse"></span> Low Stock
                                </span>
                            @else
                                <span class="text-emerald-300">In Stock</span>
                            @endif
                        </div>
                    </div>
                    <!-- Decoration -->
                    <div
                        class="absolute -bottom-8 -right-8 h-32 w-32 bg-white/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-1000">
                    </div>
                </div>
            </div>

            <!-- Right Column: Details & Descriptions -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Shipment Tracker Card -->
                @php
                    $activeTransferItem = \App\Models\WarehouseStoreTransferItem::where('product_id', $product->product_id)
                        ->whereHas('transfer', function ($q) use ($store) {
                            $q->where('to_store_id', $store->id)
                                ->where('status', '!=', 'received');
                        })
                        ->with('transfer')
                        ->orderBy('id', 'desc')
                        ->first();
                @endphp

                @if($activeTransferItem)
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125a1.125 1.125 0 001.125-1.125V3.375A1.125 1.125 0 0019.875 2.25H6.75a1.125 1.125 0 00-1.125 1.125V14.25m17.25 4.5V14.25m-17.25 0h17.25c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.315V5.25" />
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Shipment Tracking</h2>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Status</span>
                                @php
                                    $tStatus = $activeTransferItem->transfer->status;
                                    $statusColor = 'bg-slate-100 text-slate-600';
                                    $tLabel = 'Menyiapkan';
                                    if ($tStatus == 'shipping') {
                                        $statusColor = 'bg-blue-100 text-blue-700';
                                        $tLabel = 'Dalam Perjalanan';
                                    } elseif ($tStatus == 'arrived') {
                                        $statusColor = 'bg-amber-100 text-amber-700';
                                        $tLabel = 'Sampai di Store';
                                    }
                                @endphp
                                <span
                                    class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest {{ $statusColor }}">
                                    {{ $tLabel }}
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Transfer #
                                    </p>
                                    <p class="text-sm font-bold text-slate-900">
                                        {{ $activeTransferItem->transfer->transfer_number }}</p>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Quantity</p>
                                    <p class="text-sm font-bold text-slate-900">{{ $activeTransferItem->quantity }}
                                        {{ $product->product->unit }}</p>
                                </div>
                            </div>

                            @if($tStatus == 'arrived' || $tStatus == 'shipping')
                                <form action="{{ route('inventory.warehouse-to-store.receive', $activeTransferItem->transfer) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="w-full py-4 rounded-2xl bg-emerald-600 text-white text-sm font-black shadow-lg shadow-emerald-100 hover:bg-emerald-700 transition-all active:scale-[0.98]">
                                        Konfirmasi Terima Barang
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Descriptions Card -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-12">
                    <div class="flex items-center gap-3 mb-10">
                        <div class="h-10 w-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 6.75h16.5M3.75 12H12m-8.25 5.25h16.5" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Marketplace Descriptions</h2>
                    </div>

                    <div class="space-y-12">
                        <!-- Desc 1 -->
                        <div class="relative pl-8">
                            <div class="absolute left-0 top-0 text-[10px] font-black text-slate-200 mt-1">01</div>
                            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Primary
                                Specification</h3>
                            <p class="text-lg text-slate-700 font-medium leading-relaxed italic">
                                {{ $product->description_1 ?: 'No description provided.' }}
                            </p>
                        </div>

                        <!-- Desc 2 & 3 Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-0 text-[10px] font-black text-slate-200 mt-1">02</div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Secondary Info
                                </h3>
                                <p class="text-slate-600 leading-relaxed">
                                    {{ $product->description_2 ?: '-' }}
                                </p>
                            </div>
                            <div class="relative pl-8">
                                <div class="absolute left-0 top-0 text-[10px] font-black text-slate-200 mt-1">03</div>
                                <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-4">Additional
                                    Details</h3>
                                <p class="text-slate-600 leading-relaxed">
                                    {{ $product->description_3 ?: '-' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Margin Card -->
                @php
                    $hpp = $product->product->purchase_price ?? 0;
                    $jual = $product->price;
                    $mgRp = $jual - $hpp;
                    $mgPct = $hpp > 0 ? round(($mgRp / $hpp) * 100, 1) : 0;
                    $isPos = $mgRp >= 0;
                @endphp
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-8">
                        <div
                            class="h-10 w-10 rounded-xl {{ $isPos ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-500' }} flex items-center justify-center">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="{{ $isPos ? 'M2.25 18L9 11.25l4.5 4.5L21.75 7.5' : 'M2.25 6L9 12.75l4.5-4.5L21.75 16.5' }}" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-slate-900 tracking-tight">Analisis Margin</h2>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">HPP (Modal)</p>
                            <p class="text-lg font-black text-slate-700">Rp {{ number_format($hpp, 0, ',', '.') }}</p>
                        </div>
                        <div class="p-4 rounded-2xl bg-indigo-50 border border-indigo-100">
                            <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Harga Jual</p>
                            <p class="text-lg font-black text-indigo-700">Rp {{ number_format($jual, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <!-- Margin Result Badge -->
                    <div
                        class="p-5 rounded-2xl {{ $isPos ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }} flex items-center justify-between mb-4">
                        <div>
                            <p
                                class="text-[10px] font-black {{ $isPos ? 'text-emerald-500' : 'text-rose-400' }} uppercase tracking-widest mb-1">
                                Margin Bersih</p>
                            <p class="text-2xl font-black {{ $isPos ? 'text-emerald-700' : 'text-rose-600' }}">
                                {{ $isPos ? '+' : '-' }}Rp {{ number_format(abs($mgRp), 0, ',', '.') }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span
                                class="inline-flex items-center px-4 py-2 rounded-xl {{ $isPos ? 'bg-emerald-600' : 'bg-rose-600' }} text-white text-xl font-black shadow-sm">
                                {{ $isPos ? '+' : '' }}{{ $mgPct }}%
                            </span>
                        </div>
                    </div>

                    <!-- Bar visual -->
                    @if($hpp > 0)
                        <div class="space-y-1.5">
                            <div class="flex justify-between text-[9px] font-black text-slate-400 uppercase tracking-widest">
                                <span>0</span>
                                <span>HPP</span>
                                <span>Jual</span>
                            </div>
                            <div class="relative h-3 bg-slate-100 rounded-full overflow-hidden">
                                <div class="absolute left-0 top-0 h-full rounded-full {{ $isPos ? 'bg-indigo-500' : 'bg-rose-400' }} transition-all duration-700"
                                    style="width: {{ min(100, ($jual / max($hpp, $jual)) * 100) }}%"></div>
                                <div class="absolute top-0 h-full w-0.5 bg-slate-400"
                                    style="left: {{ min(100, ($hpp / max($hpp, $jual)) * 100) }}%"></div>
                            </div>
                            <p class="text-[9px] text-slate-400 text-center">
                                {{ $isPos ? 'Jual di atas HPP' : 'Jual di bawah HPP — rugi!' }}</p>
                        </div>
                    @endif
                </div>

                <!-- Admin Info / Stats -->
                <div
                    class="bg-slate-50/50 rounded-[2rem] border border-slate-100 p-8 grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Added at</p>
                        <p class="text-sm font-bold text-slate-900">{{ $product->created_at->format('M d, Y') }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Last Update</p>
                        <p class="text-sm font-bold text-slate-900">{{ $product->updated_at->diffForHumans() }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Internal ID</p>
                        <p class="text-sm font-mono font-bold text-slate-500">
                            #SP-{{ str_pad($product->id, 5, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="space-y-1">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">System Status</p>
                        <p class="text-sm font-bold text-emerald-600">Healthy</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reuse Adjustment Modal structure from index.blade.php but update the JS -->
    @include('stores.products.partials.adjust-modal')

@endsection

@push('scripts')
    <script>
        function openAdjustModal(id, name, d1, d2, d3, price, stock, isActive) {
            const form = document.getElementById('adjustForm');
            form.action = `/stores/{{ $store->id }}/products/${id}/adjust`;

            document.getElementById('modalTitle').textContent = name;
            document.getElementById('modalDesc1').value = (d1 === 'null' || d1 === '') ? '' : d1;
            document.getElementById('modalDesc2').value = (d2 === 'null' || d2 === '') ? '' : d2;
            document.getElementById('modalDesc3').value = (d3 === 'null' || d3 === '') ? '' : d3;
            document.getElementById('modalPrice').value = price;
            document.getElementById('modalStock').value = stock;
            document.getElementById('modalActive').checked = isActive === true || isActive === 'true';

            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }
    </script>
@endpush