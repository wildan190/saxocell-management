<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockTransfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockTransferController extends Controller
{
    public function index()
    {
        $transfers = StockTransfer::with(['fromWarehouse', 'toWarehouse'])->latest()->get();
        return view('inventory.transfers.index', compact('transfers'));
    }

    public function create()
    {
        $warehouses = Warehouse::all();
        $products = Product::all();
        return view('inventory.transfers.create', compact('warehouses', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'from_warehouse_id' => 'required|exists:warehouses,id',
            'to_warehouse_id' => 'required|exists:warehouses,id|different:from_warehouse_id',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request) {
            $transfer = StockTransfer::create([
                'transfer_number' => 'ST-' . strtoupper(Str::random(8)),
                'from_warehouse_id' => $request->from_warehouse_id,
                'to_warehouse_id' => $request->to_warehouse_id,
                'transfer_date' => $request->transfer_date,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                // Check stock at source
                $sourceInventory = Inventory::where('warehouse_id', $request->from_warehouse_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$sourceInventory || $sourceInventory->quantity < $item['quantity']) {
                    $product = Product::find($item['product_id']);
                    throw new \Exception("Insufficient stock for product: " . $product->name . " in source warehouse.");
                }

                // Deduct from source
                $sourceInventory->decrement('quantity', $item['quantity']);

                // Add to destination
                $destInventory = Inventory::firstOrNew([
                    'warehouse_id' => $request->to_warehouse_id,
                    'product_id' => $item['product_id'],
                ]);
                $destInventory->quantity += $item['quantity'];
                $destInventory->save();

                // Create Item record
                $transfer->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        });

        return redirect()->route('inventory.transfers.index')
            ->with('success', 'Stock transfer completed successfully.');
    }

    public function show(StockTransfer $stockTransfer)
    {
        $stockTransfer->load(['fromWarehouse', 'toWarehouse', 'items.product']);
        return view('inventory.transfers.show', compact('stockTransfer'));
    }
}
