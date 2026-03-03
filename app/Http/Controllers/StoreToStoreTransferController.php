<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\StoreToStoreTransfer;
use App\Models\StoreToStoreTransferItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreToStoreTransferController extends Controller
{
    public function index(Store $store)
    {
        $transfers = StoreToStoreTransfer::where('from_store_id', $store->id)
            ->orWhere('to_store_id', $store->id)
            ->with(['fromStore', 'toStore'])
            ->latest()
            ->get();

        return view('stores.transfers.index', compact('store', 'transfers'));
    }

    public function create(Store $store)
    {
        $otherStores = Store::where('id', '!=', $store->id)->get();
        $products = StoreProduct::where('store_id', $store->id)
            ->with('product')
            ->where('stock', '>', 0)
            ->get();

        return view('stores.transfers.create', compact('store', 'otherStores', 'products'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'to_store_id' => 'required|exists:stores,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request, $store) {
            $transfer = StoreToStoreTransfer::create([
                'from_store_id' => $store->id,
                'to_store_id' => $request->to_store_id,
                'transfer_number' => 'TRF-' . strtoupper(Str::random(8)),
                'status' => 'pending',
                'notes' => $request->notes,
                'created_by' => auth()->user()->name,
            ]);

            foreach ($request->items as $item) {
                StoreToStoreTransferItem::create([
                    'transfer_id' => $transfer->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            $this->logActivity($transfer, 'created', 'Transfer created by ' . auth()->user()->name);

            return redirect()->route('stores.transfers.show', [$store, $transfer])
                ->with('success', 'Transfer created successfully.');
        });
    }

    public function show(Store $store, StoreToStoreTransfer $transfer)
    {
        $transfer->load(['fromStore', 'toStore', 'items.product', 'logs.user']);
        return view('stores.transfers.show', compact('store', 'transfer'));
    }

    public function ship(Store $store, StoreToStoreTransfer $transfer)
    {
        if ($transfer->status !== 'pending') {
            return back()->with('error', 'Only pending transfers can be shipped.');
        }

        return DB::transaction(function () use ($transfer) {
            foreach ($transfer->items as $item) {
                $storeProduct = StoreProduct::where('store_id', $transfer->from_store_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if (!$storeProduct || $storeProduct->stock < $item->quantity) {
                    throw new \Exception('Insufficient stock for product: ' . $item->product->name);
                }

                $storeProduct->decrement('stock', $item->quantity);
            }

            $transfer->update([
                'status' => 'shipped',
                'shipped_at' => now(),
                'shipped_by' => auth()->user()->name,
            ]);

            $this->logActivity($transfer, 'shipped', 'Transfer shipped by ' . auth()->user()->name);

            return back()->with('success', 'Transfer shipped successfully.');
        });
    }

    public function receive(Store $store, StoreToStoreTransfer $transfer)
    {
        if ($transfer->status !== 'shipped') {
            return back()->with('error', 'Only shipped transfers can be received.');
        }

        return DB::transaction(function () use ($transfer) {
            foreach ($transfer->items as $item) {
                $storeProduct = StoreProduct::firstOrCreate(
                    ['store_id' => $transfer->to_store_id, 'product_id' => $item->product_id],
                    ['stock' => 0, 'price' => 0, 'is_active' => false]
                );

                $storeProduct->increment('stock', $item->quantity);
            }

            $transfer->update([
                'status' => 'received',
                'received_at' => now(),
                'received_by' => auth()->user()->name,
            ]);

            $this->logActivity($transfer, 'received', 'Transfer received by ' . auth()->user()->name);

            return back()->with('success', 'Transfer received successfully.');
        });
    }

    private function logActivity($transfer, $activity, $notes = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_id' => $transfer->id,
            'loggable_type' => StoreToStoreTransfer::class,
            'activity' => $activity,
            'notes' => $notes,
        ]);
    }
}
