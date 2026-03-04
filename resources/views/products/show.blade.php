@extends('layouts.app')

@section('content')
    <!-- Load QR library at the top to ensure it is available for inline scripts -->
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

    <div class="max-w-4xl mx-auto space-y-8 pb-20">
        <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('products.index') }}"
                class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">{{ $product->name }}</h1>
                <p class="text-sm font-mono font-bold text-indigo-600 uppercase tracking-widest mt-1">{{ $product->sku }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Product Info -->
            <div class="md:col-span-2 space-y-8">
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 md:p-10 space-y-8">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-slate-900">General Information</h2>
                        <button type="button" onclick="openPriceModal()"
                            class="text-xs font-black text-indigo-600 uppercase tracking-widest hover:underline">
                            Adjust Prices
                        </button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Unit</p>
                            <p class="font-bold text-slate-900">{{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Stock</p>
                            <p class="font-black text-indigo-600 text-lg">
                                {{ number_format($product->inventories->sum('quantity'), 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 text-red-400">
                                Current Buy</p>
                            <p class="font-black text-red-600 text-lg">
                                Rp {{ number_format($product->purchase_price, 0, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1 text-green-400">
                                Current Sell</p>
                            <p class="font-black text-green-600 text-lg">
                                Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Description</p>
                        <p class="text-slate-600 leading-relaxed">{{ $product->description ?: 'No description provided.' }}
                        </p>
                    </div>
                </div>

                <!-- Price adjustment history -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h2 class="text-xl font-bold text-slate-900">Price History</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th
                                        class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Date</th>
                                    <th
                                        class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Purchase Price</th>
                                    <th
                                        class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Selling Price</th>
                                    <th
                                        class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Reason</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($product->priceHistories as $history)
                                    <tr class="text-sm">
                                        <td class="px-8 py-4 text-slate-500 font-medium">
                                            {{ $history->created_at->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <div class="flex flex-col items-end">
                                                <span class="font-bold text-slate-900">Rp
                                                    {{ number_format($history->new_purchase_price, 0, ',', '.') }}</span>
                                                <span class="text-[10px] text-slate-400">was Rp
                                                    {{ number_format($history->old_purchase_price, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <div class="flex flex-col items-end">
                                                <span class="font-bold text-indigo-600">Rp
                                                    {{ number_format($history->new_selling_price, 0, ',', '.') }}</span>
                                                <span class="text-[10px] text-slate-400">was Rp
                                                    {{ number_format($history->old_selling_price, 0, ',', '.') }}</span>
                                            </div>
                                        </td>
                                        <td class="px-8 py-4 text-slate-600 italic">
                                            {{ $history->reason }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-8 py-8 text-center text-slate-400 italic">No price adjustments
                                            recorded.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Inventory per Warehouse -->
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h2 class="text-xl font-bold text-slate-900">Inventory Distribution</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr class="bg-slate-50/50">
                                    <th
                                        class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Warehouse</th>
                                    <th
                                        class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                        Stock Level</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($product->inventories as $inv)
                                    <tr>
                                        <td class="px-8 py-4">
                                            <span class="text-sm font-bold text-slate-700">{{ $inv->warehouse->name }}</span>
                                        </td>
                                        <td class="px-8 py-4 text-right">
                                            <span
                                                class="text-sm font-black text-indigo-600">{{ number_format($inv->quantity, 0, ',', '.') }}</span>
                                            <span
                                                class="text-[10px] font-bold text-slate-400 uppercase">{{ $product->unit }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="px-8 py-10 text-center text-sm text-slate-400 italic">This
                                            product is not in stock in any warehouse.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- QR Sidebar -->
            <div class="md:col-span-1">
                <div
                    class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-8 text-center space-y-6 sticky top-8">
                    <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">Product QR Code</h3>
                    <div id="product-qr"
                        class="flex justify-center p-8 bg-indigo-50/50 rounded-[2rem] border-2 border-indigo-100 shadow-sm mx-auto w-fit">
                    </div>
                    <div class="space-y-4">
                        <p class="text-xs text-slate-500 leading-relaxed px-4">Use this QR code for labeling and quick
                            scanning during stock opname sessions.</p>
                        <button type="button" onclick="printSingleQR()"
                            class="w-full rounded-2xl bg-slate-900 px-6 py-3.5 text-sm font-bold text-white shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all active:scale-[0.98]">
                            Print Product Label
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Price Adjustment Modal -->
    <div id="price-modal"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
        <div class="bg-white rounded-[2rem] shadow-xl w-full max-w-md p-10 animate-in fade-in zoom-in duration-300">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold text-slate-900">Adjust Prices</h2>
                <button type="button" onclick="closePriceModal()" class="text-slate-400 hover:text-slate-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form action="{{ route('products.update', $product) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Hidden fields for other required data -->
                <input type="hidden" name="name" value="{{ $product->name }}">
                <input type="hidden" name="unit" value="{{ $product->unit }}">
                <input type="hidden" name="description" value="{{ $product->description }}">

                <div class="space-y-4">
                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 px-1">Purchase Price (Buy)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold text-sm">Rp</span>
                            </div>
                            <input type="number" name="purchase_price" value="{{ (int)$product->purchase_price }}" required
                                class="block w-full pl-12 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 px-1">Selling Price (Sell)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold text-sm">Rp</span>
                            </div>
                            <input type="number" name="selling_price" value="{{ (int)$product->selling_price }}" required
                                class="block w-full pl-12 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-900 focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 px-1">Reason for change</label>
                        <textarea name="adjustment_reason" rows="2" placeholder="e.g. Market price update..."
                            class="block w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closePriceModal()"
                        class="flex-1 px-6 py-3.5 rounded-2xl bg-slate-100 text-sm font-bold text-slate-600 hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-2 px-8 py-3.5 rounded-2xl bg-indigo-600 text-sm font-bold text-white shadow-xl shadow-indigo-200 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openPriceModal() {
            document.getElementById('price-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePriceModal() {
            document.getElementById('price-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
        document.addEventListener("DOMContentLoaded", function () {
            const container = document.getElementById("product-qr");
            if (container) {
                container.innerHTML = "";
                new QRCode(container, {
                    text: "{{ $product->sku }}",
                    width: 200,
                    height: 200,
                    colorDark: "#4338ca",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.Level ? QRCode.Level.H : 2
                });
            }
        });

        function printSingleQR() {
            const sku = "{{ $product->sku }}";
            const name = "{{ $product->name }}";
            const qrCanvas = document.querySelector('#product-qr canvas');
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
                                                    }
                                                    .sku { font-size: 10pt; font-weight: bold; margin-top: 2mm; }
                                                    .name { font-size: 8pt; color: #444; margin-top: 1mm; }
                                                    img { max-width: 25mm; height: auto; }
                                                </style>
                                            </head>
                                            <body>
                                                <img src="${qrImage}" />
                                                <div class="sku">${sku}</div>
                                                <div class="name">${name}</div>
                                                <script>
                                                    window.onload = () => { window.print(); window.close(); }
                                                <\/script>
                                            </body>
                                        </html>
                                    `);
            printWindow.document.close();
        }
    </script>
@endsection