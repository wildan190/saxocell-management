<?php

namespace App\Http\Controllers;

use App\Models\GoodsReceipt;
use App\Models\Store;
use App\Models\Product;
use App\Models\StoreProduct;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\ProductPriceHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StoreGoodsReceiptController extends Controller
{
    public function index(Store $store)
    {
        $receipts = GoodsReceipt::where('store_id', $store->id)->with('admin')->latest()->get();
        return view('stores.inventory.goods-receipts.index', compact('store', 'receipts'));
    }

    public function create(Store $store)
    {
        $products = Product::all();
        $accounts = $store->financeAccounts;
        return view('stores.inventory.goods-receipts.create', compact('store', 'products', 'accounts'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'payment_accounts' => 'required|array|min:1',
            'payment_accounts.*.id' => 'required|exists:finance_accounts,id',
            'payment_accounts.*.amount' => 'required|numeric|min:0',
            'payment_accounts.*.has_fee' => 'nullable|boolean',
            'received_date' => 'required|date',
            'sender_name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|max:255',
            'items.*.name' => 'required|string|max:255',
            'items.*.category' => 'nullable|string|max:255',
            'items.*.unit' => 'required|string|max:50',
            'items.*.description' => 'nullable|string|max:1000',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $store) {
            $totalItemsCost = 0;
            foreach ($request->items as $item) {
                $totalItemsCost += $item['quantity'] * $item['purchase_price'];
            }

            $totalPaymentExcludingFees = collect($request->payment_accounts)->sum('amount');

            if (abs($totalItemsCost - $totalPaymentExcludingFees) > 0.01) {
                abort(422, "Total item (Rp " . number_format($totalItemsCost, 0, ',', '.') . ") tidak sesuai dengan total pembayaran (Rp " . number_format($totalPaymentExcludingFees, 0, ',', '.') . ").");
            }

            $adminFeeTotal = 0;
            foreach ($request->payment_accounts as $payment) {
                if (!empty($payment['has_fee'])) {
                    $adminFeeTotal += 2500;
                }
            }

            // 1. Create Goods Receipt Header
            $receipt = GoodsReceipt::create([
                'store_id' => $store->id,
                'admin_id' => auth()->id(),
                'receipt_number' => 'SGR-' . strtoupper(Str::random(8)),
                'received_date' => $request->received_date,
                'sender_name' => $request->sender_name,
                'admin_fee' => $adminFeeTotal,
                'received_by' => auth()->user()->name,
                'notes' => $request->notes,
            ]);

            // 2. Process Finance (Expenses)
            foreach ($request->payment_accounts as $payment) {
                if ($payment['amount'] <= 0)
                    continue;

                $fee = !empty($payment['has_fee']) ? 2500 : 0;
                $totalDeduction = $payment['amount'] + $fee;

                $account = FinanceAccount::lockForUpdate()->findOrFail($payment['id']);
                $account->decrement('balance', $totalDeduction);

                FinanceTransaction::create([
                    'finance_account_id' => $account->id,
                    'category' => 'Pembelian Barang',
                    'title' => 'Pembelian via ' . $receipt->receipt_number,
                    'amount' => $payment['amount'],
                    'type' => 'expense',
                    'transaction_date' => $request->received_date,
                    'notes' => "Pembelian Toko: " . number_format($payment['amount'], 0, ',', '.')
                ]);

                if ($fee > 0) {
                    FinanceTransaction::create([
                        'finance_account_id' => $account->id,
                        'category' => 'Biaya Admin',
                        'title' => 'Fee Admin via ' . $receipt->receipt_number,
                        'amount' => $fee,
                        'type' => 'expense',
                        'transaction_date' => $request->received_date,
                        'notes' => 'fee admin'
                    ]);
                }
            }

            // 3. Process Items
            foreach ($request->items as $item) {
                $product = Product::firstOrCreate(
                    ['sku' => $item['sku']],
                    [
                        'name' => $item['name'],
                        'category' => $item['category'] ?? null,
                        'unit' => $item['unit'],
                        'description' => $item['description'] ?? null,
                    ]
                );

                if ($item['category']) {
                    $product->update(['category' => $item['category']]);
                }

                // Log Price History
                $oldPurchasePrice = $product->purchase_price ?? 0;
                if ($oldPurchasePrice != $item['purchase_price']) {
                    ProductPriceHistory::create([
                        'product_id' => $product->id,
                        'old_purchase_price' => $oldPurchasePrice,
                        'new_purchase_price' => $item['purchase_price'],
                        'old_selling_price' => $product->selling_price ?? 0,
                        'new_selling_price' => $product->selling_price ?? 0,
                        'reason' => 'Store Goods Receipt ' . $receipt->receipt_number,
                        'user_id' => auth()->id(),
                    ]);
                    $product->update(['purchase_price' => $item['purchase_price']]);
                }

                $receipt->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                ]);

                // Update Store Inventory
                $storeProduct = StoreProduct::firstOrNew([
                    'store_id' => $store->id,
                    'product_id' => $product->id,
                ]);
                $storeProduct->stock += $item['quantity'];
                $storeProduct->save();
            }
        });

        return redirect()->route('stores.inventory.goods-receipts.index', $store)
            ->with('success', 'Store Goods Receipt recorded and store stock updated.');
    }

    public function show(Store $store, GoodsReceipt $goodsReceipt)
    {
        $goodsReceipt->load('items.product');
        return view('stores.inventory.goods-receipts.show', compact('store', 'goodsReceipt'));
    }
}
