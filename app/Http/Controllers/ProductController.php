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
        ]);

        Product::create($validated);

        return back()->with('success', 'Product created successfully.');
    }
}
