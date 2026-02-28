@extends('layouts.app')

@section('content')
    <div class="flex min-h-full flex-col justify-center py-20 sm:px-6 lg:px-8 bg-slate-50">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="flex justify-center">
                <a href="{{ route('marketplace.index') }}" class="group">
                    <div
                        class="h-16 w-16 rounded-2xl bg-slate-950 flex items-center justify-center shadow-2xl group-hover:rotate-6 transition-transform duration-500">
                        <svg class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                        </svg>
                    </div>
                </a>
            </div>
            <h2 class="mt-8 text-center text-4xl font-black tracking-tight text-slate-950">Management Console</h2>
            <p class="mt-2 text-center text-sm font-bold text-slate-400 uppercase tracking-widest">Sign in to access your
                dashboard</p>
        </div>

        <div class="mt-12 sm:mx-auto sm:w-full sm:max-w-md">
            <div
                class="bg-white px-8 py-12 shadow-[0_32px_64px_-12px_rgba(0,0,0,0.08)] rounded-[40px] border border-slate-100/50">
                <form class="space-y-8" action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="space-y-2">
                        <label for="email"
                            class="ml-1 block text-xs font-black uppercase tracking-widest text-slate-500">Email
                            Address</label>
                        <div class="relative group">
                            <input id="email" name="email" type="email" autocomplete="email" required
                                class="block w-full rounded-2xl border-2 border-slate-50 py-4 px-6 text-slate-950 shadow-sm ring-0 placeholder:text-slate-300 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 text-base font-bold transition-all bg-slate-50/50 @error('email') border-red-200 bg-red-50/30 @enderror"
                                placeholder="name@company.com" value="{{ old('email') }}">
                            @error('email')
                                <p class="mt-2 ml-1 text-xs font-bold text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label for="password"
                            class="ml-1 block text-xs font-black uppercase tracking-widest text-slate-500">Password</label>
                        <div class="relative group">
                            <input id="password" name="password" type="password" autocomplete="current-password" required
                                class="block w-full rounded-2xl border-2 border-slate-50 py-4 px-6 text-slate-950 shadow-sm ring-0 placeholder:text-slate-300 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 text-base font-bold transition-all bg-slate-50/50"
                                placeholder="••••••••">
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-5 w-5 rounded-lg border-2 border-slate-200 text-slate-950 focus:ring-slate-950 transition-all cursor-pointer">
                        <label for="remember" class="ml-3 block text-sm font-bold text-slate-600 cursor-pointer">Stay signed
                            in</label>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="flex w-full justify-center rounded-2xl bg-slate-950 px-6 py-4 text-sm font-black uppercase tracking-widest text-white shadow-2xl hover:bg-slate-800 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-950 transition-all active:scale-[0.98] hover:shadow-slate-200">
                            Sign In
                        </button>
                    </div>
                </form>
            </div>

            <div class="mt-8 text-center">
                <a href="{{ route('marketplace.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-slate-950 transition-colors">
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Back to Marketplace
                </a>
            </div>
        </div>
    </div>
@endsection