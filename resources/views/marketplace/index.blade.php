@extends('layouts.app')

@section('content')
    <div class="space-y-12 pb-24">
        <!-- Premium Hero Section -->
        <div
            class="relative min-h-[500px] flex items-center justify-center overflow-hidden rounded-[60px] bg-slate-950 px-8 py-32 shadow-2xl group">
            <!-- Dynamic Background Elements -->
            <div class="absolute inset-0 z-0">
                <div
                    class="absolute top-0 -left-20 w-96 h-96 bg-indigo-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-pulse">
                </div>
                <div class="absolute bottom-0 -right-20 w-96 h-96 bg-rose-600 rounded-full mix-blend-screen filter blur-[100px] opacity-20 animate-pulse"
                    style="animation-delay: 2s"></div>
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/dark-matter.png')] opacity-30">
                </div>
                <div
                    class="absolute inset-x-0 bottom-0 h-px bg-gradient-to-r from-transparent via-indigo-500/50 to-transparent">
                </div>
            </div>

            <div class="relative z-10 max-w-4xl mx-auto text-center space-y-12">
                <div class="space-y-6">
                    <div
                        class="inline-flex items-center gap-2 rounded-full glass-card px-4 py-2 text-xs font-black uppercase tracking-[0.3em] text-indigo-400 border border-indigo-500/20 shadow-xl animate-float">
                        <span class="relative flex h-2 w-2">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Verified Authentic Products
                    </div>
                    <h1 class="text-7xl font-black text-white tracking-tighter sm:text-9xl leading-[0.9] drop-shadow-2xl">
                        Saxo<span
                            class="text-transparent bg-clip-text bg-gradient-to-br from-indigo-400 to-rose-400">cell</span>
                    </h1>
                    <p class="text-xl text-slate-400 font-medium leading-relaxed max-w-2xl mx-auto drop-shadow-lg">
                        Elevate your digital life with premium technology. Handpicked authentic products from verified local
                        stores.
                    </p>
                </div>

                <!-- Premium Floating Search Bar -->
                <div class="max-w-2xl mx-auto relative group/search">
                    <form action="{{ route('marketplace.index') }}" method="GET" class="relative group">
                        <div
                            class="relative flex items-center p-1.5 bg-white shadow-[0_20px_50px_rgba(0,0,0,0.3)] rounded-[32px] transition-all duration-500 ring-4 ring-white/5 focus-within:ring-indigo-500/20 active:scale-[0.99]">
                            <div
                                class="absolute left-6 text-slate-400 group-focus-within:text-indigo-600 transition-colors pointer-events-none">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                </svg>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search for products, stores, or brands..."
                                class="w-full h-16 pl-14 pr-32 text-slate-900 placeholder:text-slate-400 focus:outline-none text-lg font-bold border-none bg-transparent">
                            <button type="submit"
                                class="absolute right-2 px-8 h-12 bg-slate-900 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-indigo-600 hover:shadow-lg hover:shadow-indigo-500/30 transition-all active:scale-95">
                                Search
                            </button>
                        </div>
                    </form>

                    @if(request('search'))
                        <div class="mt-4 flex justify-center">
                            <a href="{{ route('marketplace.index') }}"
                                class="glass-card px-4 py-2 rounded-full text-xs font-black text-slate-400 hover:text-white transition-all flex items-center gap-2 uppercase tracking-widest border border-white/10 group">
                                <svg class="w-4 h-4 text-rose-500 group-hover:rotate-90 transition-transform" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Clear results for "{{ request('search') }}"
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div id="products" class="space-y-8">
            <div class="flex items-end justify-between px-2">
                <div>
                    <h2 class="text-3xl font-black text-slate-900 tracking-tight">Featured Items</h2>
                    <p class="text-slate-500 font-medium">Handpicked selection from our diverse catalog.</p>
                </div>
                <div class="flex gap-2">
                    <!-- Category Filters (Optional) -->
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                @forelse($products as $storeProduct)
                    <div
                        class="group relative bg-white rounded-[32px] p-4 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-500 ring-1 ring-slate-100">
                        <!-- Product Image -->
                        <div class="relative aspect-square overflow-hidden rounded-2xl bg-slate-50 pb-4">
                            @if($storeProduct->image_path)
                                <img src="{{ asset('storage/' . $storeProduct->image_path) }}"
                                    alt="{{ $storeProduct->product->name }}"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div class="h-full w-full flex items-center justify-center text-slate-200">
                                    <svg class="h-20 w-20" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            @endif

                            <!-- Badges -->
                            <div class="absolute top-4 left-4">
                                <span
                                    class="rounded-full bg-white/90 backdrop-blur-md px-3 py-1 text-[10px] font-black uppercase tracking-widest text-slate-900 shadow-sm border border-slate-100">
                                    {{ $storeProduct->store->name }}
                                </span>
                            </div>
                        </div>

                        <!-- Info -->
                        <div class="mt-4 px-2 space-y-1">
                            <h3
                                class="text-lg font-black text-slate-900 tracking-tight group-hover:text-indigo-600 transition-colors">
                                <a href="{{ route('marketplace.show', $storeProduct) }}">
                                    <span class="absolute inset-0"></span>
                                    {{ $storeProduct->product->name }}
                                </a>
                            </h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                {{ $storeProduct->product->sku }}</p>

                            <div class="pt-4 flex items-center justify-between">
                                <p class="text-xl font-black text-slate-900">
                                    Rp {{ number_format($storeProduct->price, 0, ',', '.') }}
                                </p>
                                <form action="{{ route('cart.add', $storeProduct) }}" method="POST" class="relative z-10">
                                    @csrf
                                    <button type="submit"
                                        class="p-3 rounded-2xl bg-slate-900 text-white hover:bg-indigo-600 transition-all active:scale-95 shadow-lg shadow-slate-200">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center">
                        <div class="inline-flex items-center justify-center p-8 bg-slate-50 rounded-[40px] mb-6">
                            <svg class="h-16 w-16 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 italic">No products available yet.</h3>
                        <p class="text-slate-500 font-medium mt-2">Check back later for new arrivals.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
@endsection