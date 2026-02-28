<?php

namespace App\Http\Controllers;

use App\Models\StoreProduct;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = StoreProduct::where('is_active', true)->with(['product', 'store']);

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sku', 'like', '%' . $searchTerm . '%');
            });
        }

        $products = $query->get();
        return view('marketplace.index', compact('products'));
    }

    public function show(StoreProduct $product)
    {
        $product->load(['product', 'store']);
        return view('marketplace.show', compact('product'));
    }
}
