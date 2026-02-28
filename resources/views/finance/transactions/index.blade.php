@extends('layouts.app')

@section('content')
    <div class="space-y-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2 text-sm text-slate-500">
                        <li><a href="{{ $owner instanceof \App\Models\Warehouse ? route('warehouses.index') : route('stores.index') }}"
                                class="hover:text-slate-700">
                                {{ $owner instanceof \App\Models\Warehouse ? 'Warehouses' : 'Stores' }}
                            </a></li>
                        <li><svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z"
                                    clip-rule="evenodd" />
                            </svg></li>
                        <li class="font-medium text-slate-900">{{ $owner->name }}</li>
                    </ol>
                </nav>
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900 font-display">Finance Transactions
                </h1>
                <p class="mt-2 text-sm text-slate-700">Record and track income and expenses for <span
                        class="font-semibold text-indigo-600">{{ $owner->name }}</span>.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:gap-3">
                @if($owner instanceof \App\Models\Warehouse)
                    <a href="{{ route('inventory.goods-receipts.index', $owner) }}"
                        class="block rounded-xl bg-slate-900 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-[0.98]">
                        Goods Receipt
                    </a>
                @endif
                <button type="button" onclick="document.getElementById('transfer-balance-modal').classList.remove('hidden')"
                    class="block rounded-xl bg-slate-900 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-[0.98]">
                    Transfer Saldo
                </button>
                <button type="button" onclick="document.getElementById('income-modal').classList.remove('hidden')"
                    class="block rounded-xl bg-emerald-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-all active:scale-[0.98]">
                    Pemasukan Baru
                </button>
                <button type="button" onclick="document.getElementById('expense-modal').classList.remove('hidden')"
                    class="block rounded-xl bg-rose-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-rose-500 transition-all active:scale-[0.98]">
                    Biaya Baru
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-xl bg-green-50 p-4 border border-green-100 mb-6">
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

        <div class="bg-white shadow-sm ring-1 ring-slate-100 sm:rounded-2xl overflow-hidden border border-slate-100">
            <table class="min-w-full divide-y divide-slate-100">
                <thead class="bg-slate-50/50">
                    <tr>
                        <th scope="col"
                            class="py-4 pl-6 pr-3 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date
                        </th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Account
                        </th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Category
                        </th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Catatan
                        </th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Amount
                        </th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Type</th>
                        <th scope="col"
                            class="px-3 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                            Description</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($transactions as $transaction)
                        <tr class="hover:bg-slate-50/30 transition-colors">
                            <td class="whitespace-nowrap py-4 pl-6 pr-3 text-sm text-slate-600">
                                {{ $transaction->transaction_date }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm font-semibold text-slate-900">
                                {{ $transaction->title }}
                                @if($transaction->is_admin_fee)
                                    <span
                                        class="ml-2 inline-flex items-center rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-bold text-blue-700 uppercase tracking-tight">Admin
                                        Fee</span>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                <span
                                    class="inline-flex items-center rounded-lg bg-slate-50 px-2.5 py-1 text-xs font-medium text-slate-700">
                                    {{ $transaction->account->name }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-600">
                                <span class="px-2 py-1 rounded-md bg-slate-100 text-xs font-semibold text-slate-600">
                                    {{ $transaction->category }}
                                </span>
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500 italic">
                                {{ Str::limit($transaction->notes, 30) }}
                            </td>
                            <td
                                class="whitespace-nowrap px-3 py-4 text-sm font-black {{ $transaction->type === 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $transaction->type === 'income' ? '+' : '-' }} Rp
                                {{ number_format($transaction->amount, 2, ',', '.') }}
                            </td>
                            <td class="whitespace-nowrap px-3 py-4 text-sm capitalize">
                                <span
                                    class="inline-flex items-center rounded-full {{ $transaction->type === 'income' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }} px-2 py-0.5 text-xs font-bold uppercase tracking-tighter">
                                    {{ $transaction->type }}
                                </span>
                            </td>
                            <td class="px-3 py-4 text-sm text-slate-500 max-w-xs truncate">
                                {{ $transaction->description ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-sm text-slate-400 italic">No transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Expense Modal (Biaya Baru) -->
        <div id="expense-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 font-display">Catat Biaya Baru</h2>
                <form
                    action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.transactions.store', $owner) : route('stores.finance.transactions.store', $owner) }}"
                    method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="expense">

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Nama Biaya / Keperluan</label>
                        <input type="text" name="title" required placeholder="Contoh: Pembayaran Listrik, Pembelian ATK"
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Kategori Biaya</label>
                            <select name="category" required
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm">
                                <option value="Pembelian Aktiva">Pembelian Aktiva</option>
                                <option value="Cicilan Utang">Cicilan Utang</option>
                                <option value="Beban Pembelian">Beban Pembelian</option>
                                <option value="Gaji">Gaji</option>
                                <option value="Sewa">Sewa</option>
                                <option value="Listrik & Air">Listrik & Air</option>
                                <option value="Beban Pengiriman">Beban Pengiriman</option>
                                <option value="Pajak">Pajak</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}"
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Supplier (Opsional)</label>
                        <select name="supplier_id"
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm">
                            <option value="">-- Pilih Supplier --</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}
                                    ({{ $supplier->company ?? 'No Company' }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-4 pt-4 border-t border-slate-100">
                        <div class="flex items-center justify-between mb-2">
                            <label class="block text-sm font-semibold text-slate-900">Sumber Akun & Jumlah Biaya</label>
                        </div>
                        <div id="expense-splits-container" class="space-y-3">
                            <div class="split-row flex gap-3 items-end">
                                <div class="flex-[1.5]">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Akun</label>
                                    <select name="splits[0][finance_account_id]" required
                                        class="split-account block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm">
                                        @foreach($accounts as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }} (Rp
                                                {{ number_format($acc->balance, 2, ',', '.') }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Jumlah</label>
                                    <div class="relative">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="splits[0][amount]" step="0.01" required
                                            class="split-amount block w-full rounded-xl border-slate-200 pl-10 bg-slate-50/50 py-2.5 text-sm font-bold text-rose-600 transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm">
                                    </div>
                                </div>
                                <div class="flex flex-col items-center justify-center pb-2 px-1">
                                    <label
                                        class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter mb-1">Admin</label>
                                    <input type="checkbox" name="splits[0][is_admin_fee]" value="1"
                                        class="split-admin h-4 w-4 rounded border-slate-300 text-rose-600 focus:ring-rose-600">
                                </div>
                                <div class="px-1 text-center hidden remove-split-btn-container pb-2">
                                    <button type="button" onclick="removeSplitRow(this)"
                                        class="text-slate-400 hover:text-red-500 transition-colors" title="Hapus Akun">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-split-btn" onclick="addSplitRow()"
                            class="mt-3 text-xs font-semibold text-rose-600 hover:text-rose-700 flex items-center gap-1 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                                </path>
                            </svg>
                            Tambah Akun Pembayar
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Catatan (Internal)</label>
                        <textarea name="notes" rows="2" placeholder="Catatan internal untuk biaya ini..."
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-rose-600/10 focus:border-rose-600 shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" onclick="document.getElementById('expense-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Batal</button>
                        <button type="submit"
                            class="rounded-xl bg-rose-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 transition-all active:scale-[0.98]">Simpan
                            Biaya</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Income Modal (Pemasukan Baru) -->
        <div id="income-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 font-display">Catat Pemasukan Baru</h2>
                <form
                    action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.transactions.store', $owner) : route('stores.finance.transactions.store', $owner) }}"
                    method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="type" value="income">

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Nama Pemasukan / Sumber</label>
                        <input type="text" name="title" required placeholder="Contoh: Penjualan Harian, Setoran Modal"
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:border-emerald-600 shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Masuk ke Akun</label>
                            <select name="finance_account_id" required
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:border-emerald-600 shadow-sm">
                                @foreach($accounts as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }} (Rp
                                        {{ number_format($acc->balance, 2, ',', '.') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Kategori Pemasukan</label>
                            <select name="category" required
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:border-emerald-600 shadow-sm">
                                <option value="Pemasukan Modal">Pemasukan Modal</option>
                                <option value="Pinjaman">Pinjaman</option>
                                <option value="Dividen">Dividen</option>
                                <option value="Pendapatan">Pendapatan</option>
                                <option value="Lain-lain">Lain-lain</option>
                                <option value="Topup">Topup (Internal)</option>
                                <option value="Sales">Sales</option>
                                <option value="Refund">Refund</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Jumlah Pemasukan</label>
                            <div class="relative mt-2">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input type="number" name="amount" id="amount_income" step="0.01" required
                                    class="block w-full rounded-xl border-slate-200 pl-10 bg-slate-50/50 py-2.5 text-sm font-bold text-emerald-600 transition-all focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:border-emerald-600 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Tanggal Transaksi</label>
                            <input type="date" name="transaction_date" required value="{{ date('Y-m-d') }}"
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:border-emerald-600 shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Catatan (Internal)</label>
                        <textarea name="notes" rows="2" placeholder="Catatan internal untuk pemasukan ini..."
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-emerald-600/10 focus:border-emerald-600 shadow-sm"></textarea>
                    </div>

                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" onclick="document.getElementById('income-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Batal</button>
                        <button type="submit"
                            class="rounded-xl bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-all active:scale-[0.98]">Simpan
                            Pemasukan</button>
                    </div>
                </form>
            </div>
        </div>
        <!-- Transfer Balance Modal -->
        <div id="transfer-balance-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg p-8 text-left">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 font-display">Internal Balance Transfer</h2>
                <form
                    action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.transactions.transfer', $owner) : route('stores.finance.transactions.transfer', $owner) }}"
                    method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">From Account</label>
                        <select name="from_account_id" required
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                            <option value="" disabled selected>Select Source</option>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} (Rp
                                    {{ number_format($acc->balance, 2, ',', '.') }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">To Account</label>
                        <select name="to_account_id" required
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                            <option value="" disabled selected>Select Destination</option>
                            @foreach($allOwners as $targetOwner)
                                <optgroup
                                    label="{{ $targetOwner instanceof \App\Models\Warehouse ? 'Warehouse: ' : 'Store: ' }}{{ $targetOwner->name }}">
                                    @foreach($targetOwner->financeAccounts as $acc)
                                        <option value="{{ $acc->id }}">{{ $acc->name }} (Rp
                                            {{ number_format($acc->balance, 2, ',', '.') }})
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Amount</label>
                            <div class="relative mt-2">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                                </div>
                                <input type="number" name="amount" step="0.01" required
                                    class="block w-full rounded-xl border-slate-200 pl-10 bg-slate-50/50 py-2.5 text-sm font-bold transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-900">Transfer Date</label>
                            <input type="date" name="transfer_date" required value="{{ date('Y-m-d') }}"
                                class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Notes</label>
                        <textarea name="notes" rows="2" placeholder="Reason for transfer..."
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm"></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button"
                            onclick="document.getElementById('transfer-balance-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Cancel</button>
                        <button type="submit"
                            class="rounded-xl bg-slate-900 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-[0.98]">Execute
                            Transfer
                        </button>
                    </div>
                </form>
            </div>
        </div>


    </div>
    @push('scripts')
        <script>
            let splitIndex = 1;

            window.addEventListener('load', function () {
                const urlParams = new URLSearchParams(window.location.search);
                const modal = urlParams.get('modal');
                if (modal === 'income') {
                    document.getElementById('income-modal').classList.remove('hidden');
                } else if (modal === 'expense') {
                    document.getElementById('expense-modal').classList.remove('hidden');
                } else if (modal === 'transfer') {
                    document.getElementById('transfer-balance-modal').classList.remove('hidden');
                }
            });



            function addSplitRow() {
                const container = document.getElementById('expense-splits-container');
                const firstRow = container.querySelector('.split-row');
                const newRow = firstRow.cloneNode(true);

                // Reset inputs
                const amountInput = newRow.querySelector('.split-amount');
                amountInput.value = '';
                amountInput.name = `splits[${splitIndex}][amount]`;

                const accountInput = newRow.querySelector('.split-account');
                accountInput.name = `splits[${splitIndex}][finance_account_id]`;

                const adminCheckbox = newRow.querySelector('.split-admin');
                adminCheckbox.checked = false;
                adminCheckbox.name = `splits[${splitIndex}][is_admin_fee]`;

                // Show remove button
                newRow.querySelector('.remove-split-btn-container').classList.remove('hidden');

                container.appendChild(newRow);
                splitIndex++;
                updateSplitIndexes();
            }

            function removeSplitRow(btn) {
                const row = btn.closest('.split-row');
                row.remove();
                updateSplitIndexes();
            }

            function updateSplitIndexes() {
                const container = document.getElementById('expense-splits-container');
                const rows = container.querySelectorAll('.split-row');
                rows.forEach((row, index) => {
                    row.querySelector('.split-account').name = `splits[${index}][finance_account_id]`;
                    row.querySelector('.split-amount').name = `splits[${index}][amount]`;
                    row.querySelector('.split-admin').name = `splits[${index}][is_admin_fee]`;

                    if (index === 0) {
                        row.querySelector('.remove-split-btn-container').classList.add('hidden');
                    } else {
                        row.querySelector('.remove-split-btn-container').classList.remove('hidden');
                    }
                });
            }
        </script>
    @endpush
@endsection