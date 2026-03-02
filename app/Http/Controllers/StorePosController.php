<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\PosTransaction;
use App\Models\PosTransactionItem;
use App\Models\Store;
use App\Models\StoreProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorePosController extends Controller
{
    public function index(Store $store)
    {
        $transactions = PosTransaction::where('store_id', $store->id)
            ->with('items')
            ->latest()
            ->paginate(20);

        return view('stores.pos.index', compact('store', 'transactions'));
    }

    public function create(Store $store)
    {
        $products = StoreProduct::where('store_id', $store->id)
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->with('product')
            ->get();

        $accounts = FinanceAccount::where('store_id', $store->id)->get();

        return view('stores.pos.create', compact('store', 'products', 'accounts'));
    }

    public function store(Request $request, Store $store)
    {
        $request->validate([
            'cashier_name' => 'required|string|max:255',
            'payment_method' => 'required|in:cash,transfer,qris',
            'amount_paid' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'customer_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'finance_account_id' => 'required|exists:finance_accounts,id',
            'items' => 'required|array|min:1',
            'items.*.store_product_id' => 'required|exists:store_products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        DB::transaction(function () use ($request, $store) {
            $discount = $request->discount ?? 0;
            $subtotal = 0;
            $lineItems = [];

            foreach ($request->items as $item) {
                $sp = StoreProduct::lockForUpdate()->findOrFail($item['store_product_id']);

                if ($sp->stock < $item['quantity']) {
                    abort(422, "Stok produk '{$sp->product->name}' tidak mencukupi.");
                }

                $lineSubtotal = $sp->price * $item['quantity'];
                $subtotal += $lineSubtotal;

                $lineItems[] = [
                    'store_product_id' => $sp->id,
                    'product_name' => $sp->product->name,
                    'product_sku' => $sp->product->sku ?? null,
                    'quantity' => $item['quantity'],
                    'unit_price' => $sp->price,
                    'subtotal' => $lineSubtotal,
                ];

                $sp->decrement('stock', $item['quantity']);
            }

            $total = max(0, $subtotal - $discount);
            $amountPaid = $request->amount_paid;
            $change = max(0, $amountPaid - $total);
            $payLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'];
            $txNumber = 'POS-' . strtoupper($store->id . '-' . now()->format('YmdHis'));

            $transaction = PosTransaction::create([
                'store_id' => $store->id,
                'transaction_number' => $txNumber,
                'cashier_name' => $request->cashier_name,
                'customer_name' => $request->customer_name,
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $total,
                'amount_paid' => $amountPaid,
                'change_amount' => $change,
                'notes' => $request->notes,
            ]);

            foreach ($lineItems as $li) {
                $li['pos_transaction_id'] = $transaction->id;
                PosTransactionItem::create($li);
            }

            // ====================================================
            // Auto-record Finance Transaction (Pemasukan / Income)
            // ====================================================
            $account = FinanceAccount::lockForUpdate()->findOrFail($request->finance_account_id);

            FinanceTransaction::create([
                'finance_account_id' => $account->id,
                'category' => 'Penjualan POS',
                'title' => 'POS ' . $txNumber . ($request->customer_name ? ' – ' . $request->customer_name : ''),
                'type' => 'income',
                'amount' => $total,
                'transaction_date' => now()->toDateString(),
                'notes' => 'Bayar: ' . ($payLabels[$request->payment_method] ?? $request->payment_method)
                    . ($request->notes ? ' | ' . $request->notes : ''),
                'is_admin_fee' => false,
            ]);

            $account->increment('balance', $total);

            session(['last_pos_transaction_id' => $transaction->id]);
        });

        $lastId = session('last_pos_transaction_id');
        return redirect()->route('stores.pos.show', [$store, $lastId])
            ->with('success', 'Transaksi berhasil diproses dan otomatis dicatat ke keuangan.');
    }

    public function show(Store $store, PosTransaction $transaction)
    {
        $transaction->load('items.storeProduct.product');
        return view('stores.pos.show', compact('store', 'transaction'));
    }
}
