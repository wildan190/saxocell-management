@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 pb-24 space-y-12">
        <!-- Header -->
        <div class="text-center space-y-4">
            <h1 class="text-4xl font-black text-slate-900 tracking-tight sm:text-5xl">Finalize Order</h1>
            <p class="text-slate-500 font-medium">Please provide your details and upload payment proof to complete the
                purchase.</p>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Data Form -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-[40px] p-10 shadow-sm ring-1 ring-slate-100 space-y-8">
                        <div class="space-y-6">
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-white text-sm">1</span>
                                Customer Identity
                            </h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Full
                                        Name</label>
                                    <input type="text" name="customer_name" required placeholder="John Doe"
                                        class="block w-full rounded-2xl border-slate-200 py-4 px-6 font-medium text-slate-900 focus:ring-indigo-600 focus:border-indigo-600 shadow-sm">
                                </div>
                                <div>
                                    <label
                                        class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">WhatsApp
                                        / Contact</label>
                                    <input type="text" name="customer_contact" required placeholder="+62 812..."
                                        class="block w-full rounded-2xl border-slate-200 py-4 px-6 font-medium text-slate-900 focus:ring-indigo-600 focus:border-indigo-600 shadow-sm">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-6 pt-10 border-t border-slate-50">
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-white text-sm">2</span>
                                Shipping Address
                            </h2>
                            <div>
                                <label
                                    class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Full
                                    Address</label>
                                <textarea name="customer_address" required rows="3"
                                    placeholder="Street name, Building No, City, Province..."
                                    class="block w-full rounded-2xl border-slate-200 py-4 px-6 font-medium text-slate-900 focus:ring-indigo-600 focus:border-indigo-600 shadow-sm"></textarea>
                            </div>
                        </div>

                        <div class="space-y-6 pt-10 border-t border-slate-50">
                            <h2 class="text-2xl font-black text-slate-900 tracking-tight flex items-center gap-3">
                                <span
                                    class="flex h-10 w-10 items-center justify-center rounded-2xl bg-indigo-600 text-white text-sm">3</span>
                                Payment Verification
                            </h2>
                            <div
                                class="p-8 bg-indigo-50/30 rounded-[32px] border-2 border-dashed border-indigo-100 space-y-4">
                                <p class="text-center text-sm font-medium text-indigo-900">Please transfer the total amount to <br> 
                                    <span class="text-lg font-black tracking-widest text-indigo-600">BCA 5475530826</span><br>
                                    and upload the screenshot proof below.</p>
                                <div class="flex flex-col items-center">
                                    <label class="cursor-pointer group">
                                        <div class="flex flex-col items-center justify-center space-y-2">
                                            <div
                                                class="h-16 w-16 flex items-center justify-center rounded-2xl bg-white text-indigo-600 shadow-md group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                                </svg>
                                            </div>
                                            <span
                                                class="text-xs font-black uppercase tracking-widest text-indigo-600 mt-2">Upload
                                                Proof</span>
                                        </div>
                                        <input type="file" name="proof_of_transfer" required accept="image/*"
                                            class="hidden">
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Sidebar -->
                <div class="space-y-8">
                    <div class="bg-slate-900 rounded-[40px] p-8 shadow-2xl space-y-8">
                        <h3 class="text-xl font-black text-white tracking-tight">Your Order</h3>
                        <div class="space-y-4 max-h-96 overflow-y-auto pr-2 custom-scrollbar">
                            @php $total = 0; @endphp
                            @foreach($cart as $item)
                                @php $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal; @endphp
                                <div class="flex gap-4 items-center py-4 border-b border-slate-800 last:border-0 text-white">
                                    <div class="h-14 w-14 flex-shrink-0 rounded-xl bg-slate-800 overflow-hidden">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/' . $item['image']) }}" class="h-full w-full object-cover">
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-sm truncate">{{ $item['name'] }}</p>
                                        <p class="text-xs text-slate-500">{{ $item['quantity'] }} x Rp
                                            {{ number_format($item['price'], 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="pt-8 border-t border-slate-800 space-y-4">
                            <div class="flex justify-between text-slate-400 font-medium text-sm">
                                <span>Total Amount</span>
                                <span class="text-2xl font-black text-white tracking-tight">Rp
                                    {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <button type="submit"
                            class="block w-full rounded-[24px] bg-indigo-600 py-5 text-center text-sm font-black text-white shadow-xl hover:bg-indigo-500 transition-all active:scale-[0.98]">
                            Confirm Order
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 10px;
        }
    </style>
@endsection