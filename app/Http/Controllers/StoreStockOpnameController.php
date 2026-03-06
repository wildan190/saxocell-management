<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreStockOpnameController extends Controller
{
    public function index(Store $store)
    {
        $opnames = StockOpname::where('store_id', $store->id)
            ->with(['user'])
            ->latest()
            ->get();
        return view('stores.opname.index', compact('store', 'opnames'));
    }

    public function create(Store $store)
    {
        return view('stores.opname.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'notes' => 'nullable|string',
        ]);

        $opname = StockOpname::create([
            'store_id' => $store->id,
            'user_id' => auth()->id(),
            'reference_number' => 'SOPN-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $this->logActivity($opname, 'started', 'Store stock opname session started');

        return redirect()->route('stores.opname.scan', ['store' => $store, 'opname' => $opname]);
    }

    public function scan(Store $store, StockOpname $opname)
    {
        if ($opname->status !== 'pending') {
            return redirect()->route('stores.opname.show', ['store' => $store, 'opname' => $opname])->with('error', 'This opname session is already ' . $opname->status);
        }

        $opname->load(['items.product']);
        return view('stores.opname.scan', compact('store', 'opname'));
    }

    public function updateItem(Request $request, Store $store, StockOpname $opname)
    {
        $request->validate([
            'sku' => 'required|string',
            'physical_stock' => 'nullable|integer|min:0',
            'increment' => 'nullable|boolean',
        ]);

        $storeProduct = StoreProduct::where('store_id', $store->id)
            ->whereHas('product', function ($q) use ($request) {
                $q->where('sku', $request->sku);
            })
            ->with('product')
            ->first();

        if (!$storeProduct) {
            return response()->json(['message' => 'Product not found in this store'], 404);
        }

        $systemStock = $storeProduct->stock;

        $opnameItem = StockOpnameItem::where('stock_opname_id', $opname->id)
            ->where('product_id', $storeProduct->product_id)
            ->first();

        if ($request->increment) {
            $physicalStock = 1; // Stock is always 1 for unique items/used goods
        } else {
            $physicalStock = $request->physical_stock ?? 0;
        }

        $difference = $physicalStock - $systemStock;

        StockOpnameItem::updateOrCreate(
            ['stock_opname_id' => $opname->id, 'product_id' => $storeProduct->product_id],
            [
                'system_stock' => $systemStock,
                'physical_stock' => $physicalStock,
                'difference' => $difference,
            ]
        );

        return response()->json([
            'product_name' => $storeProduct->product->name,
            'system_stock' => $systemStock,
            'physical_stock' => $physicalStock,
            'difference' => $difference
        ]);
    }

    public function show(Store $store, StockOpname $opname)
    {
        $opname->load(['user', 'items.product']);
        return view('stores.opname.show', compact('store', 'opname'));
    }

    public function complete(Store $store, StockOpname $opname)
    {
        if ($opname->status !== 'pending') {
            return back()->with('error', 'This opname is already ' . $opname->status);
        }

        return DB::transaction(function () use ($store, $opname) {
            foreach ($opname->items as $item) {
                $storeProduct = StoreProduct::where('store_id', $store->id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($storeProduct) {
                    $storeProduct->update(['stock' => $item->physical_stock]);
                }
            }

            $opname->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $this->logActivity($opname, 'completed', 'Store stock opname finalized. Store inventory adjusted.');

            return redirect()->route('stores.opname.show', ['store' => $store, 'opname' => $opname])->with('success', 'Stock opname completed and store inventory adjusted.');
        });
    }

    private function logActivity($opname, $activity, $notes = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'loggable_id' => $opname->id,
            'loggable_type' => StockOpname::class,
            'activity' => $activity,
            'notes' => $notes,
        ]);
    }
}
