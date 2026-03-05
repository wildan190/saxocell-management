<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Product;
use App\Models\ProductReturn;
use App\Models\ProductReturnPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductReturnController extends Controller
{
    /**
     * Step 1: Initiate a return — input resi & shipping cost.
     * Changes status to "shipped".
     */
    public function ship(Request $request, Product $product)
    {
        $request->validate([
            'resi' => 'nullable|string|max:100',
            'shipping_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        ProductReturn::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'resi' => $request->resi,
            'shipping_cost' => $request->shipping_cost ?? 0,
            'shipped_at' => now(),
            'status' => 'shipped',
            'notes' => $request->notes,
        ]);

        return back()->with('success', 'Return berhasil dicatat. Barang sedang dalam pengiriman.');
    }

    /**
     * Step 2: Mark as arrived.
     */
    public function arrive(ProductReturn $productReturn)
    {
        $productReturn->update([
            'arrived_at' => now(),
            'status' => 'arrived',
        ]);

        return back()->with('success', 'Barang sudah dikonfirmasi telah sampai.');
    }

    /**
     * Step 3: Add a partial payment (stackable refund).
     */
    public function addPayment(Request $request, ProductReturn $productReturn)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'finance_account_id' => 'required|exists:finance_accounts,id',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $productReturn) {
            ProductReturnPayment::create([
                'product_return_id' => $productReturn->id,
                'amount' => $request->amount,
                'finance_account_id' => $request->finance_account_id,
                'payment_date' => now()->toDateString(),
                'notes' => $request->notes,
            ]);

            // Credit the finance account (money came in from supplier)
            $account = FinanceAccount::findOrFail($request->finance_account_id);
            $account->increment('balance', $request->amount);

            FinanceTransaction::create([
                'finance_account_id' => $account->id,
                'category' => 'Return Barang',
                'title' => 'Retur Produk #' . $productReturn->product->sku,
                'type' => 'income',
                'amount' => $request->amount,
                'transaction_date' => now()->toDateString(),
                'notes' => $request->notes,
                'is_admin_fee' => false,
            ]);
        });

        return back()->with('success', 'Nominal return berhasil ditambahkan.');
    }

    /**
     * Step 4: Mark as cleared — return is resolved.
     */
    public function clear(ProductReturn $productReturn)
    {
        $productReturn->update(['status' => 'cleared']);

        return back()->with('success', 'Return dinyatakan selesai (cleared).');
    }

    /**
     * Update quality label — also handles compensation for yellow items.
     */
    public function updateLabel(Request $request, Product $product)
    {
        $request->validate([
            'quality_label' => 'required|in:none,yellow,red',
            'quality_description' => 'nullable|string|max:500',
            'store_label' => 'nullable|in:none,grey,red',
            // Compensation fields (optional, for yellow only)
            'compensation_price' => 'nullable|numeric|min:0',
        ]);

        $product->update([
            'quality_label' => $request->quality_label,
            'quality_description' => $request->quality_description,
            'store_label' => $request->store_label ?? $product->store_label,
        ]);

        // If compensation price is provided (yellow items), adjust selling price
        if ($request->quality_label === 'yellow' && $request->filled('compensation_price')) {
            $old = $product->selling_price;
            $product->update(['selling_price' => $request->compensation_price]);

            \App\Models\ProductActivity::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'activity_type' => 'price_adjustment',
                'description' => 'Kompensasi harga karena kondisi kurang sesuai.',
                'old_selling_price' => $old,
                'new_selling_price' => $request->compensation_price,
                'old_purchase_price' => $product->purchase_price,
                'new_purchase_price' => $product->purchase_price,
            ]);
        }

        return back()->with('success', 'Label produk berhasil diperbarui.');
    }
}
