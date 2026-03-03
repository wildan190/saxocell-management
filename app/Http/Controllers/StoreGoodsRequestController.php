<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreGoodsRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class StoreGoodsRequestController extends Controller
{
    public function index(Store $store)
    {
        $requests = $store->goodsRequests()->with(['warehouse'])->latest()->get();
        return view('stores.goods-requests.index', compact('store', 'requests'));
    }

    public function create(Store $store)
    {
        $warehouses = Warehouse::all();
        $products = \App\Models\Product::all();
        return view('stores.goods-requests.create', compact('store', 'warehouses', 'products'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        \DB::transaction(function () use ($request, $store) {
            $goodsRequest = StoreGoodsRequest::create([
                'request_number' => 'REQ-' . strtoupper(uniqid()),
                'store_id' => $store->id,
                'warehouse_id' => $request->warehouse_id,
                'notes' => $request->notes,
                'status' => 'pending',
                'requested_by' => auth()->user()->name,
            ]);

            foreach ($request->items as $item) {
                $goodsRequest->items()->create($item);
            }
        });

        return redirect()->route('stores.goods-requests.index', $store)
            ->with('success', 'Goods request submitted successfully.');
    }

    public function show(Store $store, StoreGoodsRequest $request)
    {
        $request->load(['warehouse', 'items.product']);
        return view('stores.goods-requests.show', compact('store', 'request'));
    }

    public function receive(Store $store, StoreGoodsRequest $request)
    {
        if ($request->status !== 'shipped') {
            return redirect()->back()->with('error', 'Only shipped goods can be received.');
        }

        \DB::transaction(function () use ($store, $request) {
            foreach ($request->items as $item) {
                $storeProduct = \App\Models\StoreProduct::firstOrNew([
                    'store_id' => $store->id,
                    'product_id' => $item->product_id,
                ]);

                // Initialize default values for new products
                if (!$storeProduct->exists) {
                    $storeProduct->price = 0;
                    $storeProduct->is_active = false;
                }

                $storeProduct->stock += $item->quantity;
                $storeProduct->save();
            }

            $request->update([
                'status' => 'received',
                'received_by' => auth()->user()->name,
            ]);
        });

        return redirect()->route('stores.goods-requests.index', $store)
            ->with('success', 'Goods received and store stock updated successfully.');
    }
}
