@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <!-- Breadcrumbs -->
        <nav class="flex px-4" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2 text-sm text-slate-500">
                <li><a href="{{ route('marketplace.index') }}"
                        class="hover:text-slate-900 transition-colors">Marketplace</a></li>
                <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                            clip-rule="evenodd" />
                    </svg></li>
                <li class="font-bold text-slate-900">{{ $product->product->name }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 px-4">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <div class="aspect-square rounded-[40px] overflow-hidden bg-slate-50 ring-1 ring-slate-100 shadow-xl">
                    @if($product->image_path)
                        <img src="{{ asset('storage/' . $product->image_path) }}" class="h-full w-full object-cover">
                    @else
                        <div class="h-full w-full flex items-center justify-center text-slate-200">
                            <svg class="h-32 w-32" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-10 py-4">
                <div class="space-y-4">
                    <span
                        class="inline-flex items-center rounded-full bg-indigo-50 px-4 py-1.5 text-xs font-black uppercase tracking-widest text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                        {{ $product->store->name }}
                    </span>
                    <h1 class="text-4xl font-black text-slate-900 tracking-tight sm:text-5xl leading-tight">
                        {{ $product->product->name }}
                    </h1>
                    <p class="text-3xl font-black text-slate-900">
                        Rp {{ number_format($product->price, 0, ',', '.') }}
                    </p>
                </div>

                <div class="space-y-6 pt-10 border-t border-slate-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">Description</h3>
                        <span class="text-sm font-bold {{ $product->stock > 0 ? 'text-green-600' : 'text-rose-600' }}">
                            {{ $product->stock > 0 ? 'In Stock (' . $product->stock . ' ' . $product->product->unit . ')' : 'Out of Stock' }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 gap-4">
                        @if($product->description_1)
                            <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 flex gap-4 items-start">
                                <div
                                    class="px-2 py-0.5 rounded-lg bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase">
                                    1</div>
                                <p class="text-sm text-slate-700 font-medium">{{ $product->description_1 }}</p>
                            </div>
                        @endif
                        @if($product->description_2)
                            <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 flex gap-4 items-start">
                                <div
                                    class="px-2 py-0.5 rounded-lg bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase">
                                    2</div>
                                <p class="text-sm text-slate-700 font-medium">{{ $product->description_2 }}</p>
                            </div>
                        @endif
                        @if($product->description_3)
                            <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100 flex gap-4 items-start">
                                <div
                                    class="px-2 py-0.5 rounded-lg bg-indigo-100 text-indigo-700 text-[10px] font-black uppercase">
                                    3</div>
                                <p class="text-sm text-slate-700 font-medium">{{ $product->description_3 }}</p>
                            </div>
                        @endif
                        @if(!$product->description_1 && !$product->description_2 && !$product->description_3)
                            <p class="text-slate-400 italic font-medium">No detailed description provided for this item.</p>
                        @endif
                    </div>
                </div>

                <div class="pt-10 flex gap-4">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex-1">
                            @csrf
                            <div class="flex gap-4">
                                <div class="w-24">
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                                        class="block w-full rounded-2xl border-slate-200 py-4 text-center font-bold text-slate-900 focus:ring-indigo-600 focus:border-indigo-600">
                                </div>
                                <button type="submit"
                                    class="flex-1 rounded-2xl bg-slate-900 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-[0.98]">
                                    Add to Cart
                                </button>
                            </div>
                        </form>
                    @else
                        <button disabled
                            class="flex-1 rounded-2xl bg-slate-100 py-4 text-sm font-black text-slate-400 cursor-not-allowed uppercase tracking-widest">
                            Temporarily Unavailable
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection