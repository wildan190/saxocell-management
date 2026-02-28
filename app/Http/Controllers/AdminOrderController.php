<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['store'])->latest()->get();
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load(['store', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function confirm(Request $request, Order $order)
    {
        $order->update(['status' => 'confirmed']);
        return redirect()->back()->with('success', 'Order confirmed.');
    }

    public function ship(Request $request, Order $order)
    {
        $request->validate([
            'resi_number' => 'required|string|max:255',
        ]);

        $order->update([
            'resi_number' => $request->resi_number,
            'status' => 'shipped',
        ]);

        return redirect()->back()->with('success', 'Order shipped and resi updated.');
    }

    public function complete(Request $request, Order $order)
    {
        $order->update(['status' => 'completed']);
        return redirect()->back()->with('success', 'Order marked as completed.');
    }
}
