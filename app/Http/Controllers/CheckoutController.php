<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('marketplace.index')->with('error', 'Your cart is empty.');
        }
        return view('checkout.index', compact('cart'));
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('marketplace.index');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'required|string|max:255',
            'customer_address' => 'required|string',
            'proof_of_transfer' => 'required|image|max:2048',
        ]);

        try {
            \DB::transaction(function () use ($request, $cart) {
                $proofPath = $request->file('proof_of_transfer')->store('proofs', 'public');

                // Separate orders by store
                $itemsByStore = [];
                foreach ($cart as $item) {
                    $itemsByStore[$item['store_id']][] = $item;
                }

                foreach ($itemsByStore as $storeId => $items) {
                    $totalAmount = collect($items)->sum(function ($i) {
                        return $i['price'] * $i['quantity'];
                    });

                    $order = \App\Models\Order::create([
                        'order_number' => 'ORD-' . strtoupper(uniqid()),
                        'store_id' => $storeId,
                        'customer_name' => $request->customer_name,
                        'customer_contact' => $request->customer_contact,
                        'customer_address' => $request->customer_address,
                        'proof_of_transfer_path' => $proofPath,
                        'status' => 'pending',
                        'total_amount' => $totalAmount,
                    ]);

                    foreach ($items as $item) {
                        // Use lockForUpdate to prevent race conditions during stock deduction
                        $storeProduct = \App\Models\StoreProduct::with('product')->lockForUpdate()->find($item['id']);

                        if (!$storeProduct || $storeProduct->stock < $item['quantity']) {
                            throw new \Exception("Stok tidak mencukupi untuk produk: " . ($storeProduct->product->name ?? 'Produk tidak ditemukan'));
                        }

                        $order->items()->create([
                            'product_id' => $storeProduct->product_id,
                            'quantity' => $item['quantity'],
                            'price' => $item['price'],
                            'subtotal' => $item['price'] * $item['quantity'],
                        ]);

                        // Deduct store stock
                        $storeProduct->decrement('stock', $item['quantity']);
                    }
                }
            });
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }

        session()->forget('cart');

        return redirect()->route('marketplace.index')->with('success', 'Order placed successfully! We will contact you via WhatsApp.');
    }
}
