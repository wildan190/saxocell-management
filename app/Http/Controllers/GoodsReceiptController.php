<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Inventory;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GoodsReceiptController extends Controller
{
    public function index(Warehouse $warehouse)
    {
        $receipts = $warehouse->goodsReceipts()->latest()->get();
        return view('inventory.goods-receipts.index', compact('warehouse', 'receipts'));
    }

    public function create(Warehouse $warehouse)
    {
        $products = Product::all();
        $accounts = $warehouse->financeAccounts;
        return view('inventory.goods-receipts.create', compact('warehouse', 'products', 'accounts'));
    }

    public function store(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'payment_accounts' => 'required|array|min:1',
            'payment_accounts.*.id' => 'required|exists:finance_accounts,id',
            'payment_accounts.*.amount' => 'required|numeric|min:0',
            'received_date' => 'required|date',
            'sender_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|max:255',
            'items.*.name' => 'required|string|max:255',
            'items.*.unit' => 'required|string|max:50',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $warehouse) {
            $totalCost = 0;
            foreach ($request->items as $item) {
                $totalCost += $item['quantity'] * $item['purchase_price'];
            }

            $totalPaid = collect($request->payment_accounts)->sum('amount');
            if (abs($totalCost - $totalPaid) > 0.01) {
                // In a real app we might use validation errors, but since this is JS-validated too, a simple abort/exception is fine
                abort(422, "Total pembayaran tidak sesuai dengan total pembelian.");
            }

            // 1. Create Goods Receipt Header
            $receipt = $warehouse->goodsReceipts()->create([
                'receipt_number' => 'GR-' . strtoupper(Str::random(8)),
                'received_date' => $request->received_date,
                'sender_name' => $request->sender_name,
                'received_by' => auth()->user()->name,
                'notes' => $request->notes,
            ]);

            // 2. Process Finance (Expenses)
            foreach ($request->payment_accounts as $payment) {
                if ($payment['amount'] <= 0)
                    continue;

                $account = \App\Models\FinanceAccount::lockForUpdate()->findOrFail($payment['id']);
                $account->decrement('balance', $payment['amount']);

                \App\Models\FinanceTransaction::create([
                    'finance_account_id' => $account->id,
                    'category' => 'Pembelian Barang',
                    'title' => 'Pembelian via ' . $receipt->receipt_number,
                    'amount' => $payment['amount'],
                    'type' => 'expense',
                    'transaction_date' => $request->received_date,
                    'notes' => "Pembelian dari {$request->sender_name} (Bagian dari total " . number_format($totalCost, 0, ',', '.') . ")"
                ]);
            }

            // 3. Process Items
            foreach ($request->items as $item) {
                // Find or Create Product by SKU
                $product = Product::firstOrCreate(
                    ['sku' => $item['sku']],
                    [
                        'name' => $item['name'],
                        'unit' => $item['unit'],
                        'description' => $item['description'] ?? null,
                    ]
                );

                // Log Price History if purchase price changed
                if ($product->purchase_price != $item['purchase_price']) {
                    \App\Models\ProductPriceHistory::create([
                        'product_id' => $product->id,
                        'old_purchase_price' => $product->purchase_price,
                        'new_purchase_price' => $item['purchase_price'],
                        'old_selling_price' => $product->selling_price,
                        'new_selling_price' => $product->selling_price,
                        'reason' => 'Goods Receipt ' . $receipt->receipt_number,
                        'user_id' => auth()->id(),
                    ]);

                    $product->update(['purchase_price' => $item['purchase_price']]);
                }

                $receipt->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                ]);

                // Update Inventory
                $inventory = Inventory::firstOrNew([
                    'warehouse_id' => $warehouse->id,
                    'product_id' => $product->id,
                ]);
                $inventory->quantity += $item['quantity'];
                $inventory->save();
            }
        });

        return redirect()->route('inventory.goods-receipts.index', $warehouse)
            ->with('success', 'Goods Receipt recorded, stock updated, and finance transaction created.');
    }

    public function show(Warehouse $warehouse, GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('items.product');
        return view('inventory.goods-receipts.show', compact('warehouse', 'goodsReceipt'));
    }
}
