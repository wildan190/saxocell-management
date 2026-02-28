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
                <h1 class="text-3xl font-bold leading-tight tracking-tight text-slate-900">Finance Accounts</h1>
                <p class="mt-2 text-sm text-slate-700">Manage financial accounts for <span
                        class="font-semibold text-indigo-600">{{ $owner->name }}</span>.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0 sm:flex sm:gap-3">
                @if($owner instanceof \App\Models\Warehouse)
                    <a href="{{ route('inventory.goods-receipts.index', $owner) }}"
                        class="block rounded-xl bg-slate-900 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all active:scale-[0.98]">
                        Goods Receipt
                    </a>
                @endif
                <button type="button" onclick="document.getElementById('add-account-modal').classList.remove('hidden')"
                    class="block rounded-xl bg-indigo-600 px-4 py-3 text-center text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">
                    Add Account
                </button>
            </div>
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

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($accounts as $account)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75m0 1.5v.75m0 1.5v.75m1.5-7.5h.75m1.5 0h.75m1.5 0h.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                        </div>
                        <span
                            class="inline-flex items-center rounded-full bg-slate-100 px-2.5 py-0.5 text-xs font-medium text-slate-800">{{ $account->type }}</span>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">{{ $account->name }}</h3>
                        <p class="text-3xl font-black text-slate-900 mt-1">Rp
                            {{ number_format($account->balance, 0, ',', '.') }}
                        </p>
                    </div>
                    <div class="pt-4 border-t border-slate-50 space-y-3">
                        <div class="flex gap-2">
                            <button type="button"
                                onclick="document.getElementById('add-income-modal-{{ $account->id }}').classList.remove('hidden')"
                                class="flex-1 rounded-lg bg-green-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-green-500 transition-all uppercase tracking-wider">
                                + Income
                            </button>
                            <button type="button"
                                onclick="document.getElementById('add-expense-modal-{{ $account->id }}').classList.remove('hidden')"
                                class="flex-1 rounded-lg bg-red-600 px-3 py-2 text-xs font-bold text-white shadow-sm hover:bg-red-500 transition-all uppercase tracking-wider">
                                - Expense
                            </button>
                        </div>

                        <form
                            action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.accounts.update-balance', [$owner, $account]) : route('stores.finance.accounts.update-balance', [$owner, $account]) }}"
                            method="POST" class="flex gap-2 pt-2 border-t border-slate-50">
                            @csrf
                            <input type="number" name="amount" step="0.01" required placeholder="Quick Add..."
                                class="block w-full rounded-lg border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-all">
                            <button type="submit"
                                class="rounded-lg bg-slate-900 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-slate-800 transition-all">Add</button>
                        </form>
                    </div>

                    <!-- Income Modal for this account -->
                    <div id="add-income-modal-{{ $account->id }}"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 text-left">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 font-display">Record Income: {{ $account->name }}
                            </h2>
                            <form
                                action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.transactions.store', $owner) : route('stores.finance.transactions.store', $owner) }}"
                                method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="finance_account_id" value="{{ $account->id }}">
                                <input type="hidden" name="type" value="income">
                                <input type="hidden" name="transaction_date" value="{{ date('Y-m-d') }}">

                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Name</label>
                                    <input type="text" name="title" required placeholder="e.g. Monthly Topup"
                                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Category</label>
                                    <select name="category" required
                                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                        <option value="Topup">Topup</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Refund">Refund</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 py-2">
                                    <input type="checkbox" name="is_admin_fee" value="1"
                                        id="is_admin_fee_income_{{ $account->id }}"
                                        onchange="toggleAdminFee(this, 'amount_income_{{ $account->id }}')"
                                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 transition-all">
                                    <label for="is_admin_fee_income_{{ $account->id }}"
                                        class="text-sm font-medium text-slate-700">Use Admin Fee (Rp 2.500)</label>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Amount</label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="amount" id="amount_income_{{ $account->id }}" step="0.01"
                                            required
                                            class="block w-full rounded-xl border-slate-200 pl-10 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all font-bold">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Description</label>
                                    <textarea name="description" rows="2" placeholder="General description..."
                                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Catatan (Notes)</label>
                                    <textarea name="notes" rows="2" placeholder="Internal notes..."
                                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all"></textarea>
                                </div>
                                <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                                    <button type="button"
                                        onclick="document.getElementById('add-income-modal-{{ $account->id }}').classList.add('hidden')"
                                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Cancel</button>
                                    <button type="submit"
                                        class="rounded-xl bg-green-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 transition-all active:scale-[0.98]">Save
                                        Income</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Expense Modal for this account -->
                    <div id="add-expense-modal-{{ $account->id }}"
                        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
                        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8 text-left">
                            <h2 class="text-2xl font-bold text-slate-900 mb-6 font-display">Record Expense: {{ $account->name }}
                            </h2>
                            <form
                                action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.transactions.store', $owner) : route('stores.finance.transactions.store', $owner) }}"
                                method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="finance_account_id" value="{{ $account->id }}">
                                <input type="hidden" name="type" value="expense">
                                <input type="hidden" name="transaction_date" value="{{ date('Y-m-d') }}">

                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Name</label>
                                    <input type="text" name="title" required placeholder="e.g. Service Fee"
                                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Category</label>
                                    <select name="category" required
                                        class="mt-2 block w-full rounded-xl border-slate-200 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm transition-all">
                                        <option value="Beban Pembelian">Beban Pembelian</option>
                                        <option value="Pajak">Pajak</option>
                                        <option value="Beban Pengiriman">Beban Pengiriman</option>
                                        <option value="Gaji">Gaji</option>
                                        <option value="Sewa">Sewa</option>
                                        <option value="Listrik & Air">Listrik & Air</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>
                                <div class="flex items-center gap-2 py-2">
                                    <input type="checkbox" name="is_admin_fee" value="1"
                                        id="is_admin_fee_expense_{{ $account->id }}"
                                        onchange="toggleAdminFee(this, 'amount_expense_{{ $account->id }}')"
                                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600 transition-all">
                                    <label for="is_admin_fee_expense_{{ $account->id }}"
                                        class="text-sm font-medium text-slate-700">Use Admin Fee (Rp 2.500)</label>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Amount</label>
                                    <div class="relative mt-2">
                                        <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                            <span class="text-slate-500 sm:text-sm font-bold">Rp</span>
                                        </div>
                                        <input type="number" name="amount" id="amount_expense_{{ $account->id }}" step="0.01"
                                            required
                                            class="block w-full rounded-xl border-slate-200 pl-10 bg-slate-50/50 py-2.5 text-sm font-bold transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Description</label>
                                    <textarea name="description" rows="2" placeholder="General description..."
                                        class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-semibold text-slate-900">Catatan (Notes)</label>
                                    <textarea name="notes" rows="2" placeholder="Internal notes..."
                                        class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm"></textarea>
                                </div>
                                <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                                    <button type="button"
                                        onclick="document.getElementById('add-expense-modal-{{ $account->id }}').classList.add('hidden')"
                                        class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Cancel</button>
                                    <button type="submit"
                                        class="rounded-xl bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 transition-all active:scale-[0.98]">Save
                                        Expense</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center text-slate-500 italic">No accounts found.</div>
            @endforelse
        </div>

        <!-- Add Account Modal -->
        <div id="add-account-modal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm">
            <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-8">
                <h2 class="text-2xl font-bold text-slate-900 mb-6 font-display">Create New Account</h2>
                <form
                    action="{{ $owner instanceof \App\Models\Warehouse ? route('finance.accounts.store', $owner) : route('stores.finance.accounts.store', $owner) }}"
                    method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Name</label>
                        <input type="text" name="name" required
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Type</label>
                        <select name="type" required
                            class="mt-2 block w-full rounded-xl border-slate-200 bg-slate-50/50 px-4 py-2.5 text-sm font-medium transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                            <option value="Cash">Cash</option>
                            <option value="Bank">Bank</option>
                            <option value="Savings">Savings</option>
                            <option value="Checking">Checking</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-900">Initial Balance</label>
                        <div class="relative mt-2">
                            <div
                                class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500 sm:text-sm font-bold">
                                Rp</div>
                            <input type="number" name="balance" step="0.01" required value="0"
                                class="pl-10 block w-full rounded-xl border-slate-200 bg-slate-50/50 py-2.5 text-sm font-bold transition-all focus:bg-white focus:ring-4 focus:ring-indigo-600/10 focus:border-indigo-600 shadow-sm">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" onclick="document.getElementById('add-account-modal').classList.add('hidden')"
                            class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">Cancel</button>
                        <button type="submit"
                            class="rounded-xl bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition-all active:scale-[0.98]">Create
                            Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    </div>
    @push('scripts')
        <script>
            function toggleAdminFee(checkbox, amountInputId) {
                const amountInput = document.getElementById(amountInputId);
                if (checkbox.checked) {
                    amountInput.value = 2500;
                    amountInput.readOnly = true;
                    amountInput.classList.add('bg-slate-50', 'text-slate-500');
                } else {
                    amountInput.value = '';
                    amountInput.readOnly = false;
                    amountInput.classList.remove('bg-slate-50', 'text-slate-500');
                }
            }
        </script>
    @endpush
@endsection