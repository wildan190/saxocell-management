<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class InventoryOverviewController extends Controller
{
    public function index()
    {
        $products = Product::with('inventories.warehouse')->latest()->get();
        $warehouses = Warehouse::all();

        return view('inventory.overview', compact('products', 'warehouses'));
    }
}
