@extends('layouts.app')

@section('content')
    <!-- Load QR library at the top to ensure it is available for inline scripts in the table -->
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="flex items-center gap-3">
                    <a href="{{ route('stores.show', $store) }}"
                        class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                    <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Product Management -
                        {{ $store->name }}</h1>
                </div>
                <p class="mt-2 text-sm text-slate-700">Manage store-specific product details, descriptions, and regional
                    pricing.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100">
                <div class="flex">
                    <div class="flex-shrink-0 text-green-400">
                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
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

        <div class="bg-white rounded-3xl shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <table class="min-w-full divide-y divide-slate-100">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th scope="col" class="px-3 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">QR</th>
                        <th scope="col" class="px-3 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Product</th>
                        <th scope="col" class="px-3 py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Category</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest w-64">
                            Descriptions</th>
                        <th scope="col" class="px-3 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                        <th scope="col" class="px-3 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Tracking</th>
                        <th scope="col" class="px-3 py-4 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Stock</th>
                        <th scope="col" class="px-3 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Harga Jual</th>
                        <th scope="col" class="px-3 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Margin</th>
                        <th scope="col" class="px-3 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $storeProduct)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div id="qrcode-{{ $storeProduct->id }}"
                                        class="qrcode-small w-16 h-16 p-2 bg-indigo-50 border-2 border-indigo-100 rounded-xl cursor-pointer hover:border-indigo-500 hover:scale-105 transition-all shadow-sm flex items-center justify-center group"
                                        onclick="showFullQR('{{ $storeProduct->product->sku }}', '{{ addslashes($storeProduct->product->name) }}', {{ $storeProduct->price }})">
                                    </div>
                                    <script>
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const container = document.getElementById("qrcode-{{ $storeProduct->id }}");
                                            if (container) {
                                                container.innerHTML = "";
                                                new QRCode(container, {
                                                    text: "{{ $storeProduct->product->sku }}",
                                                    width: 128,
                                                    height: 128,
                                                    colorDark: "#4338ca",
                                                    colorLight: "#ffffff",
                                                    correctLevel: QRCode.Level ? QRCode.Level.H : 1
                                                });
                                            }
                                        });
                                    </script>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($storeProduct->image_path)
                                        <img src="{{ asset('storage/' . $storeProduct->image_path) }}" class="h-10 w-10 rounded-lg object-cover ring-1 ring-slate-100">
                                    @else
                                        <div class="h-10 w-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-300">
                                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                                        </div>
                                    @endif
                                    <div>
                                        <span class="block text-sm font-bold text-slate-900">{{ $storeProduct->product->name }}</span>
                                        <span class="block text-xs text-slate-500 font-mono">{{ $storeProduct->product->sku }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-xs">
                                <div class="space-y-1">
                                    @if($storeProduct->label)
                                        <div class="mb-2">
                                            <span class="inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-black uppercase text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                                {{ $storeProduct->label }}
                                            </span>
                                        </div>
                                    @endif
                                    
                                    @if($storeProduct->internal_description)
                                        <p class="text-[10px] text-slate-900 bg-amber-50 rounded p-1 mb-2 italic border-l-2 border-amber-400 font-medium">
                                            {{ $storeProduct->internal_description }}
                                        </p>
                                    @endif

                                    <div class="space-y-0.5 text-[10px]">
                                        @if($storeProduct->description_1) <p class="text-slate-600 truncate"><span class="font-bold">1:</span> {{ $storeProduct->description_1 }}</p> @endif
                                        @if($storeProduct->description_2) <p class="text-slate-600 truncate"><span class="font-bold">2:</span> {{ $storeProduct->description_2 }}</p> @endif
                                        @if($storeProduct->description_3) <p class="text-slate-600 truncate"><span class="font-bold">3:</span> {{ $storeProduct->description_3 }}</p> @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex flex-col items-center gap-1">
                                    <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $storeProduct->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }}">
                                        {{ $storeProduct->is_active ? 'Active' : 'Draft' }}
                                    </span>
                                    @if($storeProduct->product->store_label == 'grey')
                                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">Marketplace: Hidden</span>
                                    @elseif($storeProduct->product->store_label == 'red')
                                        <span class="text-[9px] font-black text-rose-500 uppercase tracking-tighter">Marketplace: Red</span>
                                    @endif
                                    
                                    @if($storeProduct->product->quality_label != 'none')
                                        @php
                                            $qColor = $storeProduct->product->quality_label == 'red' ? 'text-rose-600 bg-rose-50' : 'text-amber-600 bg-amber-50';
                                            $qText = $storeProduct->product->quality_label == 'red' ? 'Tidak Sesuai' : 'Kurang Sesuai';
                                        @endphp
                                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[8px] font-black uppercase tracking-widest {{ $qColor }} ring-1 ring-inset ring-current/10">
                                            {{ $qText }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $recentTransferItem = \App\Models\WarehouseStoreTransferItem::where('product_id', $storeProduct->product_id)
                                        ->whereHas('transfer', function($q) use ($store) {
                                            $q->where('to_store_id', $store->id)
                                              ->where('status', '!=', 'received');
                                        })
                                        ->with('transfer')
                                        ->orderBy('id', 'desc')
                                        ->first();
                                    
                                    if (!$recentTransferItem) {
                                        $recentTransferItem = \App\Models\WarehouseStoreTransferItem::where('product_id', $storeProduct->product_id)
                                            ->whereHas('transfer', function($q) use ($store) {
                                                $q->where('to_store_id', $store->id);
                                            })
                                            ->with('transfer')
                                            ->orderBy('id', 'desc')
                                            ->first();
                                    }
                                @endphp
                                @if($recentTransferItem)
                                    <div class="space-y-1 text-center">
                                        @php
                                            $tStatus = $recentTransferItem->transfer->status;
                                            $statusColor = 'bg-slate-50 text-slate-600 border-slate-200';
                                            $pulseColor = 'bg-slate-500';
                                            $tLabel = 'Pending';
                            
                                            if ($tStatus == 'shipping') {
                                                $statusColor = 'bg-blue-50 text-blue-600 border-blue-200';
                                                $pulseColor = 'bg-blue-500';
                                                $tLabel = 'Dikirim (OTW)';
                                            } elseif ($tStatus == 'arrived') {
                                                $statusColor = 'bg-amber-50 text-amber-600 border-amber-200';
                                                $pulseColor = 'bg-amber-500';
                                                $tLabel = 'Sampai (Check)';
                                            } elseif ($tStatus == 'received') {
                                                $statusColor = 'bg-emerald-50 text-emerald-600 border-emerald-200';
                                                $pulseColor = 'bg-emerald-500';
                                                $tLabel = 'Diterima';
                                            }
                                        @endphp
                                        <div class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full border {{ $statusColor }} text-[8px] font-black uppercase tracking-widest whitespace-nowrap">
                                            <span class="h-1 w-1 rounded-full {{ $pulseColor }} @if($tStatus != 'received') animate-pulse @endif"></span>
                                            {{ $tLabel }}
                                        </div>
                                        <div class="text-[8px] font-bold text-slate-400 truncate max-w-[80px] mx-auto" title="{{ $recentTransferItem->transfer->transfer_number }}">
                                            {{ $recentTransferItem->transfer->transfer_number }}
                                        </div>
                                    </div>
                                @else
                                    <div class="text-[8px] font-bold text-slate-300 text-center uppercase tracking-tighter">No Shipment</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold {{ $storeProduct->stock <= 5 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $storeProduct->stock }} {{ $storeProduct->product->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="text-sm font-bold text-slate-900 mb-0.5">Rp {{ number_format($storeProduct->price, 0, ',', '.') }}</div>
                                <div class="text-[10px] text-slate-400 flex flex-col">
                                    <span>Min: Rp {{ number_format($storeProduct->min_price, 0, ',', '.') }}</span>
                                    <span>Max: Rp {{ number_format($storeProduct->max_price, 0, ',', '.') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @php
                                    $hpp = $storeProduct->product->purchase_price ?? 0;
                                    $jual = $storeProduct->price;
                                    $marginRp = $jual - $hpp;
                                    $marginPct = $hpp > 0 ? round(($marginRp / $hpp) * 100, 1) : 0;
                                    $marginColor = $marginRp >= 0 ? 'text-emerald-600' : 'text-rose-500';
                                    $badgeColor = $marginRp >= 0 ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100';
                                @endphp
                                <div class="space-y-1">
                                    <div class="text-[10px] text-slate-400">HPP: Rp {{ number_format($hpp, 0, ',', '.') }}</div>
                                    <div class="inline-flex items-center gap-1 px-2 py-1 rounded-lg border {{ $badgeColor }}">
                                        <span class="text-xs font-black {{ $marginColor }}">Rp {{ number_format(abs($marginRp), 0, ',', '.') }}</span>
                                        <span class="text-[9px] font-bold {{ $marginColor }}">{{ $marginRp >= 0 ? '+' : '-' }}{{ abs($marginPct) }}%</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('stores.products.show', [$store, $storeProduct]) }}"
                                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                                        View
                                    </a>
                                    <button type="button"
                                        onclick="openAdjustModal({{ $storeProduct->id }}, '{{ addslashes($storeProduct->product->name) }}', '{{ addslashes($storeProduct->product->category ?? '') }}', '{{ addslashes($storeProduct->description_1 ?? '') }}', '{{ addslashes($storeProduct->description_2 ?? '') }}', '{{ addslashes($storeProduct->description_3 ?? '') }}', '{{ addslashes($storeProduct->internal_description ?? '') }}', '{{ addslashes($storeProduct->label ?? '') }}', {{ $storeProduct->price }}, {{ $storeProduct->min_price }}, {{ $storeProduct->max_price }}, {{ $storeProduct->stock }}, {{ $storeProduct->is_active ? 'true' : 'false' }}, {{ $storeProduct->product->purchase_price ?? 0 }})"
                                        class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-sm text-slate-500 italic">
                                No products found in this store. Transfer stock from a warehouse to see items here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- QR Modal -->
    <div id="qrModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true"
                onclick="hideFullQR()"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="relative inline-block align-bottom bg-white rounded-[2.5rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full p-8 md:p-10 border border-slate-100 z-10">
                <div class="text-center space-y-8">
                    <div>
                        <h3 class="text-2xl font-black text-slate-900 tracking-tight" id="modal-title">Product Label</h3>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mt-1">Ready to print</p>
                    </div>

                    <div class="space-y-4">
                        <div class="flex flex-col items-center">
                            <div id="full-qrcode"
                                class="flex justify-center p-8 bg-indigo-50/50 rounded-[2rem] mx-auto border-2 border-indigo-100 shadow-sm w-fit">
                            </div>
                            <p id="qr-sku-small" class="text-[10px] font-mono font-bold text-slate-400 uppercase mt-2"></p>
                        </div>

                        <div class="space-y-1">
                            <p id="qr-price" class="text-2xl font-black text-indigo-600 tracking-tight"></p>
                            <p id="qr-imei" class="text-xs font-mono font-bold text-slate-500 tracking-widest uppercase"></p>
                            <p id="qr-name" class="text-lg font-bold text-slate-900"></p>
                        </div>
                    </div>

                    <div class="pt-4 flex flex-col sm:flex-row gap-3">
                        <button type="button" onclick="printQR()"
                            class="flex-1 rounded-2xl bg-indigo-600 px-6 py-4 text-sm font-bold text-white shadow-xl shadow-indigo-100 hover:bg-slate-700 transition-all active:scale-[0.98]">
                            Print Label
                        </button>
                        <button type="button" onclick="hideFullQR()"
                            class="flex-1 rounded-2xl bg-slate-100 px-6 py-4 text-sm font-bold text-slate-600 hover:bg-slate-200 transition-all">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('stores.products.partials.adjust-modal')

    <script>
        let modalQR = null;

        function showFullQR(sku, name, price) {
            document.getElementById('qr-sku-small').innerText = sku;
            document.getElementById('qr-imei').innerText = 'IMEI: ' + sku;
            document.getElementById('qr-name').innerText = name;

            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
            document.getElementById('qr-price').innerText = formattedPrice;

            const container = document.getElementById('full-qrcode');
            container.innerHTML = '';

            modalQR = new QRCode(container, {
                text: sku,
                width: 256,
                height: 256,
                colorDark: "#4338ca",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            document.getElementById('qrModal').classList.remove('hidden');
        }

        function hideFullQR() {
            document.getElementById('qrModal').classList.add('hidden');
        }

        function printQR() {
            const sku = document.getElementById('qr-sku-small').innerText;
            const name = document.getElementById('qr-name').innerText;
            const price = document.getElementById('qr-price').innerText;
            const qrCanvas = document.querySelector('#full-qrcode canvas');
            const qrImage = qrCanvas.toDataURL("image/png");

            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                                            <html>
                                                <head>
                                                    <title>Print Label - ${sku}</title>
                                                    <style>
                                                        @page { size: 50mm 40mm; margin: 0; }
                                                        body { 
                                                            font-family: system-ui, -apple-system, sans-serif; 
                                                            margin: 0; padding: 2mm; 
                                                            text-align: center; 
                                                            display: flex; flex-direction: column; 
                                                            align-items: center; justify-content: center; 
                                                            height: 36mm;
                                                            overflow: hidden;
                                                            border: 1px dashed #eee;
                                                        }
                                                        .qr-section { margin-bottom: 2mm; }
                                                        .qr-section img { max-width: 18mm; height: auto; display: block; margin: 0 auto; }
                                                        .sku-tiny { font-size: 6pt; font-family: monospace; color: #444; margin-top: 0.5mm; font-weight: bold; }
                                                        
                                                        .price { font-size: 13pt; font-weight: 900; color: #000; margin: 1.5mm 0; border-top: 1px solid #000; border-bottom: 1px solid #000; width: 100%; padding: 1mm 0; }
                                                        .imei { font-size: 9pt; font-weight: bold; margin-top: 1mm; font-family: monospace; word-break: break-all; }
                                                        .name { font-size: 8pt; color: #333; margin-top: 0.5mm; font-weight: 700; line-height: 1.1; max-height: 2.2em; overflow: hidden; }
                                                    </style>
                                                </head>
                                                <body>
                                                    <div class="qr-section">
                                                        <img src="${qrImage}" />
                                                        <div class="sku-tiny">${sku}</div>
                                                    </div>
                                                    <div class="price">${price}</div>
                                                    <div class="imei">IMEI: ${sku}</div>
                                                    <div class="name">${name}</div>
                                                    <script>
                                                        window.onload = () => { window.print(); window.close(); }
                                                    <\/script>
                                                </body>
                                            </html>
                                        `);
            printWindow.document.close();
        }

        function openAdjustModal(id, name, category, d1, d2, d3, internal_desc, label, price, min_price, max_price, stock, isActive, purchase_price) {
            const form = document.getElementById('adjustForm');
            form.action = `/stores/{{ $store->id }}/products/${id}/adjust`;

            document.getElementById('modalTitle').textContent = name;
            document.getElementById('modalCategory').value = category;
            document.getElementById('modalDesc1').value = d1;
            document.getElementById('modalDesc2').value = d2;
            document.getElementById('modalDesc3').value = d3;
            document.getElementById('modalInternalDesc').value = internal_desc;
            document.getElementById('modalLabel').value = label;
            document.getElementById('modalPrice').value = price;
            document.getElementById('modalMinPrice').value = min_price;
            document.getElementById('modalMaxPrice').value = max_price;
            document.getElementById('modalStock').value = stock;
            document.getElementById('modalActive').checked = isActive === true || isActive === 'true';
            
            // Format Purchase Price
            const formattedPurchase = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(purchase_price);
            document.getElementById('modalPurchaseDisplay').textContent = formattedPurchase;

            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }
    </script>
@endsection