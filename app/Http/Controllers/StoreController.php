<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\PosTransaction;
use App\Models\TradeIn;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    public function index()
    {
        $stores = Store::latest()->get();
        return view('stores.index', compact('stores'));
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        return view('stores.create');
    }

    /**
     * Store a newly created store in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        Store::create($validated);

        return redirect()->route('stores.index')
            ->with('success', 'Store created successfully.');
    }

    /**
     * Display the store detail dashboard.
     */
    public function show(Store $store)
    {
        // 1. Financial Context
        $accounts = $store->financeAccounts;
        $totalBalance = $accounts->sum('balance');

        // 2. Inventory Context
        $storeProducts = $store->storeProducts()->with('product')->get();

        // 3. POS Context (Store Specific)
        $todayPosRevenue = PosTransaction::where('store_id', $store->id)
            ->whereDate('created_at', today())
            ->sum('total_amount');

        $recentPosTransactions = PosTransaction::where('store_id', $store->id)
            ->latest()
            ->take(5)
            ->get();

        // 4. Goods Receipt Context (Store Specific)
        $recentGoodsReceipts = \App\Models\GoodsReceipt::where('store_id', $store->id)
            ->latest()
            ->take(5)
            ->get();

        // 5. Activity Context (Recent 5)
        $recentTransactions = $store->financeAccounts()
            ->with([
                'transactions' => function ($q) {
                    $q->latest()->take(10);
                }
            ])
            ->get()
            ->flatMap->transactions
            ->sortByDesc('created_at')
            ->take(5);

        $recentTransfers = \App\Models\WarehouseStoreTransfer::where('to_store_id', $store->id)
            ->with(['fromWarehouse'])
            ->latest()
            ->take(5)
            ->get();

        return view('stores.show', compact(
            'store',
            'accounts',
            'totalBalance',
            'storeProducts',
            'todayPosRevenue',
            'recentPosTransactions',
            'recentGoodsReceipts',
            'recentTransactions',
            'recentTransfers'
        ));
    }

    /**
     * Show the form for editing the specified store.
     */
    public function edit(Store $store)
    {
        return view('stores.edit', compact('store'));
    }

    /**
     * Update the specified store in storage.
     */
    public function update(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
        ]);

        $store->update($validated);

        return redirect()->route('stores.index')
            ->with('success', 'Store updated successfully.');
    }

    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();

        return redirect()->route('stores.index')
            ->with('success', 'Store deleted successfully.');
    }
}
