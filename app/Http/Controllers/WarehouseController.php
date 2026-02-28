<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the warehouses.
     */
    public function index()
    {
        $warehouses = Warehouse::latest()->get();
        return view('warehouses.index', compact('warehouses'));
    }

    /**
     * Show the form for creating a new warehouse.
     */
    public function create()
    {
        return view('warehouses.create');
    }

    /**
     * Store a newly created warehouse in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'capacity' => 'nullable|integer|min:0',
            'description' => 'nullable|string',
        ]);

        Warehouse::create($validated);

        return redirect()->route('warehouses.index')
            ->with('success', 'Warehouse created successfully.');
    }

    /**
     * Display the warehouse detail dashboard.
     */
    public function show(Warehouse $warehouse)
    {
        // 1. Financial Context
        $accounts = $warehouse->financeAccounts;
        $totalBalance = $accounts->sum('balance');

        // 2. Inventory Context
        $inventories = $warehouse->inventories()->with('product')->get();

        // 3. Activity Context (Recent 5 of each)
        $recentTransactions = $warehouse->financeAccounts()
            ->with('transactions')
            ->get()
            ->flatMap->transactions
            ->sortByDesc('created_at')
            ->take(5);

        $recentReceipts = $warehouse->goodsReceipts()
            ->with('items.product')
            ->latest()
            ->take(5)
            ->get();

        $recentTransfers = \App\Models\StockTransfer::where('from_warehouse_id', $warehouse->id)
            ->orWhere('to_warehouse_id', $warehouse->id)
            ->with(['fromWarehouse', 'toWarehouse'])
            ->latest()
            ->take(5)
            ->get();

        $recentStoreRequests = $warehouse->incomingRequests()
            ->with(['store'])
            ->latest()
            ->take(5)
            ->get();

        return view('warehouses.show', compact(
            'warehouse',
            'accounts',
            'totalBalance',
            'inventories',
            'recentTransactions',
            'recentReceipts',
            'recentTransfers',
            'recentStoreRequests'
        ));
    }
}
