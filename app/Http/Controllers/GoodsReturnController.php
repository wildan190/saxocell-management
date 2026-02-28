<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\GoodsReturn;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class GoodsReturnController extends Controller
{
    public function index(Warehouse $warehouse = null)
    {
        $query = GoodsReturn::with(['goodsReceipt.warehouse', 'items.product'])->latest();

        if ($warehouse && $warehouse->exists) {
            $query->whereHas('goodsReceipt', function ($q) use ($warehouse) {
                $q->where('warehouse_id', $warehouse->id);
            });
        }

        $returns = $query->get();

        return view('inventory.goods-returns.index', compact('warehouse', 'returns'));
    }

    public function create(Warehouse $warehouse, GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('items.product');
        return view('inventory.goods-returns.create', compact('warehouse', 'goodsReceipt'));
    }

    public function store(Request $request, Warehouse $warehouse, GoodsReceipt $goodsReceipt)
    {
        $request->validate([
            'reason' => 'required|string',
            'resolution' => 'required|string',
            'adjusted_price' => 'nullable|numeric|min:0',
            'proof_photos.*' => 'nullable|image|max:2048',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:0',
        ]);

        $return = DB::transaction(function () use ($request, $goodsReceipt) {
            $proofPhotos = [];
            if ($request->hasFile('proof_photos')) {
                foreach ($request->file('proof_photos') as $photo) {
                    $proofPhotos[] = $photo->store('goods-returns', 'public');
                }
            }

            $goodsReturn = $goodsReceipt->returns()->create([
                'return_number' => 'RET-' . strtoupper(Str::random(8)),
                'reason' => $request->reason,
                'resolution' => $request->resolution,
                'adjusted_price' => $request->adjusted_price,
                'proof_photos' => $proofPhotos,
                'status' => 'pending',
            ]);

            foreach ($request->items as $item) {
                if ($item['quantity'] > 0) {
                    $goodsReturn->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                    ]);
                }
            }

            return $goodsReturn;
        });

        return redirect()->route('inventory.goods-returns.show', [$warehouse, $return])
            ->with('success', 'Goods Return recorded successfully.');
    }

    public function show(Warehouse $warehouse, GoodsReturn $goodsReturn)
    {
        $goodsReturn->load(['goodsReceipt.warehouse', 'items.product']);
        return view('inventory.goods-returns.show', compact('goodsReturn'));
    }

    public function downloadPdf(Warehouse $warehouse, GoodsReturn $goodsReturn)
    {
        $goodsReturn->load(['goodsReceipt.warehouse', 'items.product']);
        $pdf = Pdf::loadView('inventory.goods-returns.pdf', compact('goodsReturn'));
        return $pdf->download('Return-' . $goodsReturn->return_number . '.pdf');
    }
}
