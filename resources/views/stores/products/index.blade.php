@extends('layouts.app')

@section('content')
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
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Product</th>
                        <th class="px-6 py-4 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Descriptions</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Status</th>
                        <th class="px-6 py-4 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Stock</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Price</th>
                        <th class="px-6 py-4 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($products as $storeProduct)
                        <tr class="hover:bg-slate-50/50 transition-colors">
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
                                <div class="space-y-1 text-[10px]">
                                    @if($storeProduct->description_1) <p class="text-slate-600 truncate"><span class="font-bold">1:</span> {{ $storeProduct->description_1 }}</p> @endif
                                    @if($storeProduct->description_2) <p class="text-slate-600 truncate"><span class="font-bold">2:</span> {{ $storeProduct->description_2 }}</p> @endif
                                    @if($storeProduct->description_3) <p class="text-slate-600 truncate"><span class="font-bold">3:</span> {{ $storeProduct->description_3 }}</p> @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider {{ $storeProduct->is_active ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $storeProduct->is_active ? 'Active' : 'Draft' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-bold {{ $storeProduct->stock <= 5 ? 'bg-rose-50 text-rose-600' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $storeProduct->stock }} {{ $storeProduct->product->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-bold text-slate-900">
                                Rp {{ number_format($storeProduct->price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('stores.products.show', [$store, $storeProduct]) }}"
                                        class="rounded-lg bg-slate-100 px-3 py-1.5 text-xs font-bold text-slate-600 hover:bg-slate-200 transition-colors">
                                        View
                                    </a>
                                    <button type="button"
                                        onclick="openAdjustModal({{ $storeProduct->id }}, '{{ addslashes($storeProduct->product->name) }}', '{{ $storeProduct->description_1 }}', '{{ $storeProduct->description_2 }}', '{{ $storeProduct->description_3 }}', {{ $storeProduct->price }}, {{ $storeProduct->stock }}, {{ $storeProduct->is_active ? 'true' : 'false' }})"
                                        class="rounded-lg bg-indigo-50 px-3 py-1.5 text-xs font-bold text-indigo-600 hover:bg-indigo-100 transition-colors">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-sm text-slate-500 italic">
                                No products found in this store. Transfer stock from a warehouse to see items here.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @include('stores.products.partials.adjust-modal')

    <script>
        function openAdjustModal(id, name, d1, d2, d3, price, stock, isActive) {
            const form = document.getElementById('adjustForm');
            form.action = `/stores/{{ $store->id }}/products/${id}/adjust`;

            document.getElementById('modalTitle').textContent = name;
            document.getElementById('modalDesc1').value = d1 === 'null' ? '' : d1;
            document.getElementById('modalDesc2').value = d2 === 'null' ? '' : d2;
            document.getElementById('modalDesc3').value = d3 === 'null' ? '' : d3;
            document.getElementById('modalPrice').value = price;
            document.getElementById('modalStock').value = stock;
            document.getElementById('modalActive').checked = isActive === true || isActive === 'true';

            document.getElementById('adjustModal').classList.remove('hidden');
        }

        function closeAdjustModal() {
            document.getElementById('adjustModal').classList.add('hidden');
        }
    </script>
@endsection