<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Http\Request;

class StoreProductController extends Controller
{
    /**
     * Display store products.
     */
    public function index(Store $store)
    {
        $products = $store->storeProducts()->with('product')->get();
        return view('stores.products.index', compact('store', 'products'));
    }

    /**
     * Display the specified store product.
     */
    public function show(Store $store, StoreProduct $product)
    {
        $product->load('product');
        return view('stores.products.show', compact('store', 'product'));
    }

    /**
     * Adjust store product details (description, price, stock).
     */
    public function adjust(Request $request, Store $store, StoreProduct $product)
    {
        $validated = $request->validate([
            'description_1' => 'nullable|string',
            'description_2' => 'nullable|string',
            'description_3' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image_path'] = $path;
        }

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('stores.products.index', $store)
            ->with('success', 'Product adjusted successfully.');
    }
}
