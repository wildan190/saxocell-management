<nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center gap-8">
                <a href="{{ route('marketplace.index') }}" class="flex items-center gap-2 group">
                    <div
                        class="h-8 w-8 rounded-lg bg-indigo-600 flex items-center justify-center shadow-lg shadow-indigo-200 group-hover:rotate-6 transition-transform">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                    <span class="text-xl font-black text-slate-900 tracking-tighter">Saxo<span
                            class="text-indigo-600">Cell</span></span>
                </a>
                <div class="hidden md:flex items-center gap-6">
                    <a href="{{ route('marketplace.index') }}"
                        class="text-sm font-bold {{ request()->routeIs('marketplace.index') ? 'text-indigo-600' : 'text-slate-500 hover:text-slate-900' }} transition-colors">Marketplace</a>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('cart.index') }}"
                    class="relative p-2 rounded-xl text-slate-500 hover:bg-slate-50 transition-all group">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.143-1.119 1.143H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0z" />
                    </svg>
                    @php $cartCount = count(session('cart', [])); @endphp
                    @if($cartCount > 0)
                        <span
                            class="absolute top-1 right-1 flex h-4 w-4 items-center justify-center rounded-full bg-indigo-600 text-[10px] font-bold text-white shadow-sm ring-2 ring-white group-hover:scale-110 transition-transform">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
                <div class="h-6 w-px bg-slate-200 mx-2"></div>
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-bold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-95">
                        Console
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="text-sm font-bold text-slate-500 hover:text-slate-900 px-4 py-2 transition-colors">Login</a>
                @endauth
            </div>
        </div>
    </div>
</nav>