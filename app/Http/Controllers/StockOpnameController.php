<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use App\Models\StockOpname;
use App\Models\StockOpnameItem;
use App\Models\Warehouse;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StockOpnameController extends Controller
{
    public function index(Request $request)
    {
        $query = StockOpname::with(['warehouse', 'user'])->latest();

        if ($request->has('warehouse_id')) {
            $query->where('warehouse_id', $request->warehouse_id);
        }

        $opnames = $query->get();
        return view('inventory.opname.index', compact('opnames'));
    }

    public function create(Request $request)
    {
        $selectedWarehouseId = $request->query('warehouse_id');
        $warehouses = Warehouse::all();
        return view('inventory.opname.create', compact('warehouses', 'selectedWarehouseId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'warehouse_id' => 'required|exists:warehouses,id',
            'notes' => 'nullable|string',
        ]);

        $opname = StockOpname::create([
            'warehouse_id' => $request->warehouse_id,
            'user_id' => auth()->id(),
            'reference_number' => 'OPN-' . strtoupper(Str::random(8)),
            'status' => 'pending',
            'notes' => $request->notes,
        ]);

        $this->logActivity($opname, 'started', 'Stock opname session started');

        return redirect()->route('inventory.opname.scan', $opname);
    }

    public function scan(StockOpname $opname)
    {
        if ($opname->status !== 'pending') {
            return redirect()->route('inventory.opname.show', $opname)->with('error', 'This opname session is already ' . $opname->status);
        }

        $opname->load(['warehouse', 'items.product']);
        return view('inventory.opname.scan', compact('opname'));
    }

    public function updateItem(Request $request, StockOpname $opname)
    {
        $request->validate([
            'sku' => 'required|string',
            'physical_stock' => 'required|integer|min:0',
        ]);

        $product = Product::where('sku', $request->sku)->first();
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        $inventory = Inventory::where('warehouse_id', $opname->warehouse_id)
            ->where('product_id', $product->id)
            ->first();

        $systemStock = $inventory ? $inventory->quantity : 0;
        $difference = $request->physical_stock - $systemStock;

        $item = StockOpnameItem::updateOrCreate(
            ['stock_opname_id' => $opname->id, 'product_id' => $product->id],
            [
                'system_stock' => $systemStock,
                'physical_stock' => $request->physical_stock,
                'difference' => $difference,
            ]
        );

        return response()->json([
            'product_name' => $product->name,
            'system_stock' => $systemStock,
            'physical_stock' => $request->physical_stock,
            'difference' => $difference
        ]);
    }

    public function show(StockOpname $opname)
    {
        $opname->load(['warehouse', 'user', 'items.product']);
        return view('inventory.opname.show', compact('opname'));
    }

    public function complete(StockOpname $opname)
    {
        if ($opname->status !== 'pending') {
            return back()->with('error', 'This opname is already ' . $opname->status);
        }

        return DB::transaction(function () use ($opname) {
            foreach ($opname->items as $item) {
                $inventory = Inventory::firstOrCreate(
                    ['warehouse_id' => $opname->warehouse_id, 'product_id' => $item->product_id],
                    ['quantity' => 0]
                );

                $inventory->update(['quantity' => $item->physical_stock]);
            }

            $opname->update([
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $this->logActivity($opname, 'completed', 'Stock opname finalized. Inventory adjusted.');

            return redirect()->route('inventory.opname.show', $opname)->with('success', 'Stock opname completed and inventory adjusted.');
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
