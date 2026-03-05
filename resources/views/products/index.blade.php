@extends('layouts.app')

@section('content')
    <!-- Load QR library at the top to ensure it is available for inline scripts in the table -->
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>

    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Product Master</h1>
                <p class="mt-2 text-sm text-slate-700">Manage your product каталог and specifications across locations.</p>
            </div>
        </div>

        <!-- Warehouse Tabs -->
        <div class="border-b border-slate-200">
            <nav class="-mb-px flex space-x-8 overflow-x-auto no-scrollbar" aria-label="Tabs">
                <a href="{{ route('products.index') }}"
                    class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold transition-all {{ !$warehouseId ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:border-slate-300 hover:text-slate-600' }}">
                    All Warehouses
                </a>
                @foreach($warehouses as $wh)
                    <a href="{{ route('products.index', ['warehouse_id' => $wh->id]) }}"
                        class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-bold transition-all {{ $warehouseId == $wh->id ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-slate-400 hover:border-slate-300 hover:text-slate-600' }}">
                        {{ $wh->name }}
                    </a>
                @endforeach
            </nav>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
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

        <div class="bg-white shadow-sm ring-1 ring-slate-100 rounded-2xl overflow-hidden mt-8">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-6 pr-3 text-left text-sm font-semibold text-slate-900">QR Code</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">SKU</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Name</th>
                        @if($warehouseId)
                            <th scope="col" class="px-3 py-3.5 text-center text-sm font-semibold text-indigo-600">Stock</th>
                        @endif
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900">Unit</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-slate-900 text-right pr-6">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3">
                                <div class="flex items-center">
                                    <div id="qrcode-{{ $product->id }}"
                                        class="qrcode-small w-16 h-16 p-2 bg-indigo-50 border-2 border-indigo-100 rounded-xl cursor-pointer hover:border-indigo-500 hover:scale-105 transition-all shadow-sm flex items-center justify-center group"
                                        onclick="showFullQR('{{ $product->sku }}', '{{ $product->name }}', {{ $product->selling_price ?? 0 }})">
                                    </div>
                                    <script>
                                        // Use high contrast for maximum visibility
                                        document.addEventListener("DOMContentLoaded", function () {
                                            const container = document.getElementById("qrcode-{{ $product->id }}");
                                            if (container) {
                                                container.innerHTML = "";
                                                new QRCode(container, {
                                                    text: "{{ $product->sku }}",
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
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-medium text-slate-900">
                                {{ $product->sku }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600 font-medium">
                                {{ $product->name }}
                            </td>
                            @if($warehouseId)
                                <td class="whitespace-nowrap px-3 py-4 text-sm text-center font-black text-indigo-600">
                                    {{ number_format($product->inventories->first()?->quantity ?? 0, 0, ',', '.') }}
                                </td>
                            @endif
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">
                                {{ $product->unit }}
                            </td>
                            <td class="px-3 py-4 text-sm text-right pr-6">
                                <a href="{{ route('products.show', $product) }}"
                                    class="text-indigo-600 hover:text-indigo-900 font-bold">Details</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ $warehouseId ? 6 : 5 }}" class="py-12 text-center text-slate-500 italic">No products
                                found.</td>
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

                    <div id="full-qrcode"
                        class="flex justify-center p-8 bg-indigo-50/50 rounded-[2rem] mx-auto border-2 border-indigo-100 shadow-sm w-fit">
                    </div>

                    <div class="space-y-1">
                        <p id="qr-price" class="text-2xl font-black text-indigo-600 tracking-tight"></p>
                        <p id="qr-sku" class="text-xs font-mono font-bold text-slate-400 tracking-widest uppercase"></p>
                        <p id="qr-name" class="text-lg font-bold text-slate-900"></p>
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


    <script>
        let modalQR = null;

        function showFullQR(sku, name, price) {
            document.getElementById('qr-sku').innerText = sku;
            document.getElementById('qr-name').innerText = name;

            const formattedPrice = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(price);
            document.getElementById('qr-price').innerText = formattedPrice;
            document.getElementById('qr-price').dataset.rawPrice = price;

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
            const sku = document.getElementById('qr-sku').innerText;
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
                                                            margin: 0; padding: 1mm; 
                                                            text-align: center; 
                                                            display: flex; flex-direction: column; 
                                                            align-items: center; justify-content: center; 
                                                            height: 38mm;
                                                            overflow: hidden;
                                                        }
                                                        .price { font-size: 14pt; font-weight: 900; color: #000; margin-bottom: 1mm; border-bottom: 1px solid #eee; width: 100%; padding-bottom: 1mm; }
                                                        .sku { font-size: 9pt; font-weight: bold; margin-top: 1mm; font-family: monospace; }
                                                        .name { font-size: 7pt; color: #555; margin-top: 0.5mm; font-weight: 600; }
                                                        img { max-width: 20mm; height: auto; margin-top: 1mm; }
                                                    </style>
                                                </head>
                                                <body>
                                                    <div class="price">${price}</div>
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