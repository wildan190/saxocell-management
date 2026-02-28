<?php

namespace App\Http\Controllers;

use App\Models\StoreGoodsRequest;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseGoodsRequestController extends Controller
{
    public function index(Warehouse $warehouse)
    {
        $requests = StoreGoodsRequest::where('warehouse_id', $warehouse->id)
            ->with(['store'])
            ->latest()
            ->get();
        return view('warehouses.incoming-requests.index', compact('warehouse', 'requests'));
    }

    public function show(Warehouse $warehouse, StoreGoodsRequest $goodsRequest)
    {
        $goodsRequest->load(['store', 'items.product']);
        return view('warehouses.incoming-requests.show', compact('warehouse', 'goodsRequest'));
    }

    public function confirm(Warehouse $warehouse, StoreGoodsRequest $goodsRequest)
    {
        \Log::info('Confirming goods request', [
            'warehouse_id' => $warehouse->id,
            'goods_request_id' => $goodsRequest->id,
            'current_status' => $goodsRequest->status
        ]);

        $goodsRequest->update(['status' => 'confirmed']);

        \Log::info('Goods request confirmed', [
            'goods_request_id' => $goodsRequest->id,
            'new_status' => $goodsRequest->fresh()->status
        ]);

        return redirect()->back()->with('success', 'Request confirmed.');
    }

    public function ship(Warehouse $warehouse, StoreGoodsRequest $goodsRequest)
    {
        \Log::info('Shipping goods request', [
            'warehouse_id' => $warehouse->id,
            'goods_request_id' => $goodsRequest->id
        ]);

        try {
            \DB::transaction(function () use ($warehouse, $goodsRequest) {
                // Ensure we have the items
                $goodsRequest->load('items.product');

                foreach ($goodsRequest->items as $item) {
                    $inventory = \App\Models\Inventory::where('warehouse_id', $warehouse->id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if (!$inventory || $inventory->quantity < $item->quantity) {
                        throw new \Exception("Insufficient stock for product: " . ($item->product->name ?? 'Unknown ID ' . $item->product_id));
                    }

                    $inventory->decrement('quantity', $item->quantity);
                }

                $goodsRequest->update(['status' => 'shipped']);
            });

            \Log::info('Goods request shipped successfully', ['id' => $goodsRequest->id]);
            return redirect()->back()->with('success', 'Goods shipped successfully.');
        } catch (\Exception $e) {
            \Log::error('Shipping failed', [
                'goods_request_id' => $goodsRequest->id,
                'error' => $e->getMessage()
            ]);
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
