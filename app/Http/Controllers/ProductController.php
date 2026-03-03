<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $warehouseId = $request->query('warehouse_id');
        $warehouses = \App\Models\Warehouse::all();

        $query = Product::query();

        if ($warehouseId) {
            $query->whereHas('inventories', function ($q) use ($warehouseId) {
                $q->where('warehouse_id', $warehouseId);
            })->with([
                        'inventories' => function ($q) use ($warehouseId) {
                            $q->where('warehouse_id', $warehouseId);
                        }
                    ]);
        }

        $products = $query->latest()->get();
        return view('products.index', compact('products', 'warehouses', 'warehouseId'));
    }

    public function show(Product $product)
    {
        $product->load('inventories.warehouse');
        return view('products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sku' => 'required|string|unique:products',
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
        ]);

        Product::create($validated);

        return back()->with('success', 'Product created successfully.');
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'adjustment_reason' => 'nullable|string|max:255',
        ]);

        // Check for price changes to log history
        if ($product->purchase_price != $request->purchase_price || $product->selling_price != $request->selling_price) {
            \App\Models\ProductPriceHistory::create([
                'product_id' => $product->id,
                'old_purchase_price' => $product->purchase_price,
                'new_purchase_price' => $request->purchase_price,
                'old_selling_price' => $product->selling_price,
                'new_selling_price' => $request->selling_price,
                'reason' => $request->adjustment_reason ?: 'Manual Adjustment',
                'user_id' => auth()->id(),
            ]);
        }

        $product->update($validated);

        return back()->with('success', 'Product updated successfully.');
    }
}
