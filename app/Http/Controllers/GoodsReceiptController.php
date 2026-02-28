<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoodsReceiptController extends Controller
{
    public function index(Warehouse $warehouse)
    {
        $receipts = $warehouse->goodsReceipts()->latest()->get();
        return view('inventory.goods-receipts.index', compact('warehouse', 'receipts'));
    }

    public function create(Warehouse $warehouse)
    {
        $products = Product::all();
        return view('inventory.goods-receipts.create', compact('warehouse', 'products'));
    }

    public function store(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'received_date' => 'required|date',
            'sender_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|max:255',
            'items.*.name' => 'required|string|max:255',
            'items.*.unit' => 'required|string|max:50',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $warehouse) {
            $receipt = $warehouse->goodsReceipts()->create([
                'receipt_number' => 'GR-' . strtoupper(Str::random(8)),
                'received_date' => $request->received_date,
                'sender_name' => $request->sender_name,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                // Find or Create Product by SKU
                $product = Product::firstOrCreate(
                    ['sku' => $item['sku']],
                    [
                        'name' => $item['name'],
                        'unit' => $item['unit'],
                        'description' => $item['description'] ?? null,
                    ]
                );

                $receipt->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                ]);

                // Update Inventory
                $inventory = Inventory::firstOrNew([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                ]);

                $inventory->quantity += $item['quantity'];
                $inventory->save();
            }
        });

        return redirect()->route('inventory.goods-receipts.index', $warehouse)
            ->with('success', 'Goods Receipt recorded and stock updated.');
    }

    public function show(Warehouse $warehouse, GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('items.product');
        return view('inventory.goods-receipts.show', compact('warehouse', 'goodsReceipt'));
    }
}
