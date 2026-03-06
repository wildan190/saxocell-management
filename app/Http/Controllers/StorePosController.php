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
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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
            ->whereHas('product', function ($q) {
                $q->where('status', 'available');
            })
            ->with('product')
            ->get();

        $accounts = FinanceAccount::where('store_id', $store->id)->get();

        return view('stores.pos.create', compact('store', 'products', 'accounts'));
    }

    public function store(Request $request, Store $store)
    {
        $transaction = null;
        DB::transaction(function () use ($request, $store, &$transaction) {
            $request->validate([
                'is_trade_in' => 'nullable|boolean',
                'trade_in_device_name' => 'required_if:is_trade_in,1|nullable|string|max:255',
                'trade_in_imei' => 'required_if:is_trade_in,1|nullable|string|max:255',
                'trade_in_customer_price' => 'required_if:is_trade_in,1|nullable|numeric|min:0',
            ]);

            $discount = $request->discount ?? 0;
            $subtotal = 0;
            $lineItems = [];

            foreach ($request->items as $item) {
                $sp = StoreProduct::lockForUpdate()->findOrFail($item['store_product_id']);

                if ($sp->stock < $item['quantity']) {
                    throw ValidationException::withMessages([
                        'stock' => ["Stok produk '{$sp->product->name}' tidak mencukupi."]
                    ]);
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

            $tradeInPrice = $request->is_trade_in ? (float) $request->trade_in_customer_price : 0;
            $netTotal = ($subtotal - $discount) - $tradeInPrice;
            $isStorePaying = $netTotal < 0;
            $absTotal = abs($netTotal);

            $payLabels = ['cash' => 'Tunai', 'transfer' => 'Transfer', 'qris' => 'QRIS'];
            $txNumber = 'POS-' . strtoupper($store->id . '-' . now()->format('YmdHis'));

            // ── Split Payment logic ───────────────────────────────────────
            $paymentSplits = null;
            $payNoteDetail = '';

            if ($request->payment_method === 'split') {
                $splits = [];
                foreach ($payLabels as $key => $label) {
                    $amt = (float) ($request->input('split_' . $key) ?? 0);
                    $accId = $request->input('split_' . $key . '_account_id');
                    if ($amt > 0) {
                        $acc = \App\Models\FinanceAccount::find($accId);
                        $splits[] = [
                            'method' => $key,
                            'label' => $label,
                            'amount' => $amt,
                            'finance_account_id' => $accId,
                            'account_name' => $acc ? $acc->name : '-'
                        ];
                    }
                }
                $amountPaid = collect($splits)->sum('amount');
                if ($amountPaid < $absTotal) {
                    $labelPrefix = $isStorePaying ? 'Pembayaran Toko' : 'Pembayaran Customer';
                    throw ValidationException::withMessages([
                        'split_total' => ["{$labelPrefix} (Rp " . number_format($amountPaid, 0, ',', '.') . ") kurang dari total transaksi (Rp " . number_format($absTotal, 0, ',', '.') . ")."]
                    ]);
                }
                $paymentSplits = $splits;
                $payNoteDetail = collect($splits)
                    ->map(fn($s) => $s['label'] . ' Rp ' . number_format($s['amount'], 0, ',', '.'))
                    ->join(' + ');
            } else {
                $amountPaid = (float) $request->amount_paid;
                $payNoteDetail = $payLabels[$request->payment_method] ?? $request->payment_method;
            }

            $change = max(0, $amountPaid - $absTotal);

            // ── Handle Traded-In Device Creation ─────────────────────────
            $tradeInProductId = null;
            if ($request->is_trade_in) {
                $tradedProduct = \App\Models\Product::create([
                    'name' => $request->trade_in_device_name,
                    'sku' => $request->trade_in_imei ?: 'TI-' . \Illuminate\Support\Str::random(8),
                    'category' => 'Smartphone (Trade-In)',
                    'purchase_price' => $tradeInPrice,
                    'unit' => 'pcs',
                    'status' => 'available',
                    'description' => 'Trade-In from ' . ($request->customer_name ?: 'Customer') . ' via POS ' . $txNumber,
                ]);

                $tradeInProductId = $tradedProduct->id;

                StoreProduct::create([
                    'store_id' => $store->id,
                    'product_id' => $tradedProduct->id,
                    'price' => $tradeInPrice * 1.2, // Default markup 20%
                    'stock' => 1,
                    'is_active' => true,
                ]);
            }

            $transaction = PosTransaction::create([
                'store_id' => $store->id,
                'transaction_number' => $txNumber,
                'cashier_name' => $request->cashier_name,
                'customer_name' => $request->customer_name,
                'payment_method' => $request->payment_method,
                'payment_splits' => $paymentSplits,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total_amount' => $absTotal,
                'amount_paid' => $amountPaid,
                'change_amount' => $change,
                'is_trade_in' => $request->is_trade_in ? true : false,
                'trade_in_device_name' => $request->trade_in_device_name,
                'trade_in_imei' => $request->trade_in_imei,
                'trade_in_customer_price' => $tradeInPrice,
                'trade_in_product_id' => $tradeInProductId,
                'notes' => $request->notes,
            ]);

            foreach ($lineItems as $li) {
                $li['pos_transaction_id'] = $transaction->id;
                PosTransactionItem::create($li);

                // Mark associated product unit as sold
                $sp = StoreProduct::find($li['store_product_id']);
                if ($sp && $sp->product) {
                    $sp->product->update(['status' => 'sold']);
                }
            }


            // ====================================================
            // Auto-record Finance Transaction
            // ====================================================
            $txType = $isStorePaying ? 'expense' : 'income';
            $txCategory = $request->is_trade_in ? 'Penjualan & Trade-In' : 'Penjualan POS';

            if ($request->payment_method === 'split') {
                foreach ($paymentSplits as $split) {
                    $methodKey = $split['method'];
                    $accountId = $request->input("split_{$methodKey}_account_id");

                    if ($accountId && $split['amount'] > 0) {
                        $account = FinanceAccount::lockForUpdate()->findOrFail($accountId);

                        FinanceTransaction::create([
                            'finance_account_id' => $account->id,
                            'category' => $txCategory,
                            'title' => 'POS ' . $txNumber . ($request->customer_name ? ' – ' . $request->customer_name : ''),
                            'type' => $txType,
                            'amount' => $split['amount'],
                            'transaction_date' => now()->toDateString(),
                            'notes' => 'Bayar (' . $split['label'] . '): ' . $payNoteDetail
                                . ($request->notes ? ' | ' . $request->notes : ''),
                            'is_admin_fee' => false,
                        ]);

                        if ($txType === 'income') {
                            $account->increment('balance', $split['amount']);
                        } else {
                            $account->decrement('balance', $split['amount']);
                        }
                    }
                }
            } else {
                $account = FinanceAccount::lockForUpdate()->findOrFail($request->finance_account_id);

                FinanceTransaction::create([
                    'finance_account_id' => $account->id,
                    'category' => $txCategory,
                    'title' => 'POS ' . $txNumber . ($request->customer_name ? ' – ' . $request->customer_name : ''),
                    'type' => $txType,
                    'amount' => $absTotal,
                    'transaction_date' => now()->toDateString(),
                    'notes' => 'Bayar: ' . $payNoteDetail
                        . ($request->notes ? ' | ' . $request->notes : ''),
                    'is_admin_fee' => false,
                ]);

                if ($txType === 'income') {
                    $account->increment('balance', $absTotal);
                } else {
                    $account->decrement('balance', $absTotal);
                }
            }

        });

        return redirect()->route('stores.pos.show', [$store, $transaction])
            ->with('success', 'Transaksi berhasil diproses dan otomatis dicatat ke keuangan.');
    }

    public function show(Store $store, PosTransaction $transaction)
    {
        $transaction->load('items.storeProduct.product');
        return view('stores.pos.show', compact('store', 'transaction'));
    }
}
