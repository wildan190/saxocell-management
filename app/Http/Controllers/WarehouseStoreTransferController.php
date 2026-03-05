<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Store;
use App\Models\WarehouseStoreTransfer;
use App\Models\StoreProduct;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WarehouseStoreTransferController extends Controller
{
    /**
     * Display a listing of transfers.
     */
    public function index()
    {
        $transfers = WarehouseStoreTransfer::with(['fromWarehouse', 'toStore'])->latest()->get();
        return view('inventory.warehouse-to-store.index', compact('transfers'));
    }

    /**
     * Show the form for creating a new transfer.
     */
    public function create()
    {
        $warehouses = Warehouse::all();
        $stores = Store::all();
        $products = \App\Models\Product::all();

        // Pass warehouse stock data for frontend validation if needed
        $warehouseStock = Inventory::all()->groupBy('warehouse_id');

        return view('inventory.warehouse-to-store.create', compact('warehouses', 'stores', 'products', 'warehouseStock'));
    }

    /**
     * Store a newly created transfer.
     */
    public function store(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_store_id' => 'required|exists:stores,id',
            'transfer_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $transfer = WarehouseStoreTransfer::create([
                'transfer_number' => 'WST-' . strtoupper(Str::random(8)),
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_store_id' => $request->to_store_id,
                'transfer_date' => $request->transfer_date,
                'notes' => $request->notes,
                'status' => 'shipping', // Initial status
            ]);

            foreach ($request->items as $item) {
                // 1. Check Warehouse Stock
                $inventory = Inventory::where('warehouse_id', $request->from_warehouse_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    $productName = \App\Models\Product::find($item['product_id'])->name;
                    throw new \Exception("Insufficient stock for {$productName} in source warehouse.");
                }

                // 2. Decrement Warehouse Stock
                $inventory->decrement('quantity', $item['quantity']);

                // 3. Record Item
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return redirect()->route('inventory.warehouse-to-store.index')
                ->with('success', 'Stock transferred to store successfully.');
        });
    }

    /**
     * Display the transfer detail.
     */
    public function show(WarehouseStoreTransfer $transfer)
    {
        $transfer->load(['fromWarehouse', 'toStore', 'items.product']);
        return view('inventory.warehouse-to-store.show', compact('transfer'));
    }

    /**
     * Mark the transfer as shipped.
     */
    public function ship(Request $request, WarehouseStoreTransfer $transfer)
    {
        $request->validate([
            'shipping_number' => 'nullable|string|max:255',
            'shipping_cost' => 'nullable|numeric|min:0',
        ]);

        $transfer->update([
            'status' => 'shipping',
            'shipping_number' => $request->shipping_number,
            'shipping_cost' => $request->shipping_cost ?? 0,
        ]);

        return back()->with('success', 'Transfer status updated to Shipping.');
    }

    /**
     * Mark the transfer as arrived at destination.
     */
    public function arrive(WarehouseStoreTransfer $transfer)
    {
        $transfer->update(['status' => 'arrived']);
        return back()->with('success', 'Transfer status updated to Arrived.');
    }

    /**
     * Receive the transfer and update store stock.
     */
    public function receive(WarehouseStoreTransfer $transfer)
    {
        if ($transfer->status === 'received') {
            return back()->with('error', 'This transfer has already been received.');
        }

        return DB::transaction(function () use ($transfer) {
            foreach ($transfer->items as $item) {
                $storeProduct = StoreProduct::firstOrCreate(
                    [
                        'store_id' => $transfer->to_store_id,
                        'product_id' => $item['product_id'],
                    ],
                    [
                        'stock' => 0,
                        'price' => 0,
                    ]
                );
                $storeProduct->increment('stock', $item['quantity']);
            }

            $transfer->update(['status' => 'received']);

            return back()->with('success', 'Transfer received and store stock updated.');
        });
    }
}
