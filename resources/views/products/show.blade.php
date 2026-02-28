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
                    <h2 class="text-xl font-bold text-slate-900">General Information</h2>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Unit</p>
                            <p class="font-bold text-slate-900">{{ $product->unit }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Stock</p>
                            <p class="font-black text-indigo-600 text-xl">
                                {{ number_format($product->inventories->sum('quantity'), 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Description</p>
                        <p class="text-slate-600 leading-relaxed">{{ $product->description ?: 'No description provided.' }}
                        </p>
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


    <script>
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