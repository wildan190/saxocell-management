<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="h-full antialiased text-slate-900">
    <div id="app" class="min-h-full">
        @auth
            <!-- Alpine App Wrapper -->
            <div x-data="{ sidebarOpen: false }" class="h-full">
                <!-- Off-canvas menu for mobile, show/hide based on off-canvas menu state. -->
                <div x-show="sidebarOpen" class="relative z-50 lg:hidden" x-ref="dialog" aria-modal="true"
                    style="display: none;">
                    <!-- Backdrop -->
                    <div x-show="sidebarOpen" x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-linear duration-300"
                        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                        class="fixed inset-0 bg-slate-900/80"></div>

                    <div class="fixed inset-0 flex">
                        <!-- Off-canvas menu panel -->
                        <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                            x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                            x-transition:leave="transition ease-in-out duration-300 transform"
                            x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                            class="relative mr-16 flex w-full max-w-xs flex-1" @click.away="sidebarOpen = false">
                            <!-- Close button -->
                            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                <button type="button" class="-m-2.5 p-2.5" @click="sidebarOpen = false">
                                    <span class="sr-only">Close sidebar</span>
                                    <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                        stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Mobile Sidebar Content -->
                            <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 ring-1 ring-white/10">
                                @include('layouts.partials.sidebar')
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Static sidebar for desktop -->
                <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
                    <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-slate-900 border-r border-slate-800">
                        @include('layouts.partials.sidebar')
                    </div>
                </div>

                <!-- Mobile Top Header -->
                <div class="sticky top-0 z-40 flex items-center gap-x-6 bg-slate-900 px-4 py-4 shadow-sm sm:px-6 lg:hidden">
                    <button type="button" class="-m-2.5 p-2.5 text-slate-400 hover:text-white transition-colors"
                        @click="sidebarOpen = true">
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div class="flex-1 text-sm font-semibold leading-6 text-white">SaxoCell</div>
                    <div class="h-8 w-8 rounded-full bg-slate-800 flex items-center justify-center text-xs text-white">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                </div>

                <!-- Main Desktop Layout Content -->
                <div class="lg:pl-72 h-full">
                    <main class="py-10">
                        <div class="px-4 sm:px-6 lg:px-8">
                            @yield('content')
                        </div>
                    </main>
                </div>
            </div>
        @else
            @include('layouts.partials.nav')
            <main>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    @yield('content')
                </div>
            </main>
        @endauth
    </div>
    @stack('scripts')
</body>

</html>