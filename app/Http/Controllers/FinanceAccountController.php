<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\Warehouse;
use App\Models\Store;
use Illuminate\Http\Request;

class FinanceAccountController extends Controller
{
    /**
     * Display a listing of financial accounts for a specific warehouse or store.
     */
    public function index(Warehouse $warehouse = null, Store $store = null)
    {
        $owner = $warehouse ?? $store;
        $accounts = $owner->financeAccounts()->latest()->get();
        return view('finance.accounts.index', compact('accounts', 'owner'));
    }

    /**
     * Store a newly created account for a specific warehouse or store.
     */
    public function store(Request $request, Warehouse $warehouse = null, Store $store = null)
    {
        $owner = $warehouse ?? $store;
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'balance' => 'required|numeric|min:0',
        ]);

        $owner->financeAccounts()->create($validated);

        $route = $warehouse ? 'finance.accounts.index' : 'stores.finance.accounts.index';
        return redirect()->route($route, $owner)
            ->with('success', 'Finance account created successfully.');
    }

    /**
     * Update the balance of an account manually.
     */
    public function updateBalance(Request $request, Warehouse $warehouse = null, Store $store = null, FinanceAccount $account)
    {
        $owner = $warehouse ?? $store;
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $account->increment('balance', $validated['amount']);

        $route = $warehouse ? 'finance.accounts.index' : 'stores.finance.accounts.index';
        return redirect()->route($route, $owner)
            ->with('success', 'Balance updated successfully.');
    }
    /**
     * Display transaction history for a specific warehouse account.
     */
    public function showWarehouseAccount(Warehouse $warehouse, FinanceAccount $account)
    {
        $owner = $warehouse;
        $transactions = $account->transactions()->latest()->paginate(20);
        return view('finance.accounts.show', compact('account', 'owner', 'transactions'));
    }

    /**
     * Display transaction history for a specific store account.
     */
    public function showStoreAccount(Store $store, FinanceAccount $account)
    {
        $owner = $store;
        $transactions = $account->transactions()->latest()->paginate(20);
        return view('finance.accounts.show', compact('account', 'owner', 'transactions'));
    }
}
