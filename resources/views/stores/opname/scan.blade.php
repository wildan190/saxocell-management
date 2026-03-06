@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto space-y-8 pb-20">
        <div class="sm:flex sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('stores.opname.index', $store) }}"
                    class="p-2 rounded-xl bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-black tracking-tight text-slate-900">{{ $opname->reference_number }}</h1>
                    <p class="text-sm text-slate-500 font-bold uppercase tracking-widest leading-none mt-1">
                        {{ $store->name }}
                    </p>
                </div>
            </div>

            <form action="{{ route('stores.opname.complete', ['store' => $store, 'opname' => $opname]) }}" method="POST">
                @csrf
                <button type="submit"
                    onclick="return confirm('Finalize this stock opname? This will adjust your store stock levels permanently.')"
                    class="rounded-xl bg-indigo-600 px-8 py-3 text-sm font-bold text-white shadow-xl shadow-indigo-100 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                    Finalize Opname
                </button>
            </form>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Scanner Column -->
            <div class="space-y-6">
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-8 border-b border-slate-50">
                        <h2 class="text-xl font-bold text-slate-900">Scan Product QR</h2>
                    </div>
                    <div class="p-8 bg-slate-50/50">
                        <div id="reader" style="width: 100%;"
                            class="rounded-2xl overflow-hidden shadow-inner border border-slate-200"></div>
                        <div class="mt-4 flex justify-center gap-3">
                            <button id="start-btn"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-lg font-bold text-xs hover:bg-indigo-500 transition-all">Start
                                Camera</button>
                            <button id="stop-btn"
                                class="px-4 py-2 bg-slate-200 text-slate-600 rounded-lg font-bold text-xs hover:bg-slate-300 transition-all hidden">Stop
                                Camera</button>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-900 rounded-[2.5rem] p-10 text-white shadow-xl shadow-indigo-100">
                    <h3 class="text-lg font-bold mb-6">Manual SKU Input</h3>
                    <div class="flex gap-4">
                        <input type="text" id="manual-sku" placeholder="Enter SKU manually..."
                            class="flex-1 bg-indigo-800/50 border border-indigo-700/50 rounded-xl px-4 py-3 text-sm text-white placeholder-indigo-400 focus:ring-indigo-500 focus:border-indigo-500">
                        <button onclick="handleManualInput()"
                            class="px-6 py-2 bg-white text-indigo-900 rounded-xl font-bold text-sm hover:bg-indigo-50 transition-all">Search</button>
                    </div>
                </div>
            </div>

            <!-- Counter Form Column -->
            <div id="counter-card"
                class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col hidden">
                <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-black text-slate-900" id="current-p-name">Product Name</h2>
                        <span id="current-p-sku"
                            class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-lg text-xs font-mono font-bold">SKU-001</span>
                    </div>
                </div>
                <div class="p-10 flex-1 space-y-10">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">System Stock</p>
                            <p id="current-p-sys" class="text-4xl font-black text-slate-400">0</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest mb-2">Physical Count
                            </p>
                            <div class="relative">
                                <input type="number" id="physical-input"
                                    class="block w-full text-4xl font-black text-indigo-600 border-0 border-b-2 border-indigo-100 focus:ring-0 focus:border-indigo-600 p-0 pb-2 transition-all"
                                    oninput="calculateDiff()">
                            </div>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 rounded-3xl border border-slate-100">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-bold text-slate-600">Difference</p>
                            <span id="diff-display" class="text-xl font-black text-slate-900">0</span>
                        </div>
                    </div>

                    <div class="pt-6 text-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">You can manually
                            adjust this value below if needed</p>
                        <button id="update-btn" onclick="submitCount()"
                            class="w-full rounded-2xl bg-indigo-600 py-4 text-center text-sm font-bold text-white shadow-xl shadow-indigo-100 hover:bg-indigo-500 transition-all active:scale-[0.98]">
                            Save Absolute Value
                        </button>
                    </div>
                </div>
            </div>

            <div id="empty-state"
                class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-20 flex flex-col items-center justify-center text-center space-y-4">
                <div class="h-20 w-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-300">
                    <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Ready to Scan</h3>
                    <p class="text-sm text-slate-500">Scan product QR code or enter SKU manually. Scanning automatically
                        increments count by 1.</p>
                </div>
            </div>
        </div>

        <!-- Scanned Items Table -->
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mt-12">
            <div class="px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900">Already Counted</h3>
                <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold"
                    id="item-count">{{ $opname->items->count() }} Items</span>
            </div>
            <table class="min-w-full divide-y divide-slate-100" id="scanned-table">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th class="px-8 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Product</th>
                        <th class="px-8 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            System</th>
                        <th class="px-8 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Physical</th>
                        <th class="px-8 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">Diff
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($opname->items as $item)
                        <tr>
                            <td class="px-8 py-4">
                                <p class="text-sm font-bold text-slate-900 capitalize">{{ $item->product->name }}</p>
                                <p class="text-[10px] font-mono text-slate-400">{{ $item->product->sku }}</p>
                            </td>
                            <td class="px-8 py-4 text-center text-sm text-slate-600">{{ $item->system_stock }}</td>
                            <td class="px-8 py-4 text-center text-sm font-bold text-indigo-600">{{ $item->physical_stock }}</td>
                            <td class="px-8 py-4 text-right">
                                <span
                                    class="text-sm font-bold {{ $item->difference < 0 ? 'text-rose-600' : ($item->difference > 0 ? 'text-emerald-600' : 'text-slate-400') }}">
                                    {{ $item->difference > 0 ? '+' : '' }}{{ $item->difference }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script>
        const html5QrCode = new Html5Qrcode("reader");
        const startBtn = document.getElementById('start-btn');
        const stopBtn = document.getElementById('stop-btn');
        const counterCard = document.getElementById('counter-card');
        const emptyState = document.getElementById('empty-state');

        let isProcessing = false;
        let lastScannedSku = '';
        let lastScannedTime = 0;

        function onScanSuccess(decodedText, decodedResult) {
            handleSku(decodedText);
        }

        function handleManualInput() {
            const sku = document.getElementById('manual-sku').value;
            if (sku) handleSku(sku);
        }

        function handleSku(sku) {
            if (isProcessing) return;

            // Debounce/Cooldown: Prevent rapid-fire scanning of the same item
            const now = Date.now();
            if (sku === lastScannedSku && (now - lastScannedTime) < 2000) {
                console.log('Debounced: ' + sku);
                return;
            }

            isProcessing = true;
            lastScannedSku = sku;
            lastScannedTime = now;
            currentSku = sku;

            fetch(`{{ route('stores.opname.update-item', ['store' => $store, 'opname' => $opname]) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sku: sku,
                    increment: true
                })
            })
                .then(res => res.json())
                .then(data => {
                    isProcessing = false;
                    if (data.product_name) {
                        emptyState.classList.add('hidden');
                        counterCard.classList.remove('hidden');
                        document.getElementById('current-p-name').innerText = data.product_name;
                        document.getElementById('current-p-sku').innerText = sku;
                        document.getElementById('current-p-sys').innerText = data.system_stock;
                        document.getElementById('physical-input').value = data.physical_stock;
                        calculateDiff();
                        showToast(`Scanned: ${data.product_name} (+1)`);
                        refreshTable();
                    } else {
                        alert(data.message || 'Product not found');
                    }
                })
                .catch(err => {
                    isProcessing = false;
                    console.error(err);
                    alert('Error fetching product data');
                });
        }

        function refreshTable() {
            fetch(window.location.href)
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.getElementById('scanned-table');
                    const newItemCount = doc.getElementById('item-count');

                    document.getElementById('scanned-table').innerHTML = newTable.innerHTML;
                    document.getElementById('item-count').innerText = newItemCount.innerText;
                });
        }

        function showToast(message) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-24 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-6 py-3 rounded-2xl font-bold text-sm shadow-2xl z-50 transition-all duration-300 translate-y-20 opacity-0';
            toast.innerText = message;
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.classList.remove('translate-y-20', 'opacity-0');
            }, 100);

            setTimeout(() => {
                toast.classList.add('translate-y-20', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function calculateDiff() {
            const sys = parseInt(document.getElementById('current-p-sys').innerText) || 0;
            const phy = parseInt(document.getElementById('physical-input').value) || 0;
            const diff = phy - sys;

            const display = document.getElementById('diff-display');
            display.innerText = (diff > 0 ? '+' : '') + diff;

            display.className = 'text-xl font-black ' +
                (diff < 0 ? 'text-rose-600' : (diff > 0 ? 'text-emerald-600' : 'text-slate-900'));
        }

        function submitCount() {
            const phy = parseInt(document.getElementById('physical-input').value);
            if (isNaN(phy)) return alert('Please enter physical count');

            fetch(`{{ route('stores.opname.update-item', ['store' => $store, 'opname' => $opname]) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    sku: currentSku,
                    physical_stock: phy
                })
            })
                .then(res => res.json())
                .then(data => {
                    location.reload();
                })
                .catch(err => alert('Error saving count'));
        }

        startBtn.addEventListener('click', () => {
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length > 0) {
                    html5QrCode.start(
                        { facingMode: "environment" },
                        { fps: 10, qrbox: { width: 250, height: 250 } },
                        onScanSuccess
                    );
                    startBtn.classList.add('hidden');
                    stopBtn.classList.remove('hidden');
                }
            }).catch(err => alert("Camera permission denied or not found"));
        });

        stopBtn.addEventListener('click', () => {
            html5QrCode.stop().then(() => {
                startBtn.classList.remove('hidden');
                stopBtn.classList.add('hidden');
            });
        });
    </script>
@endsection