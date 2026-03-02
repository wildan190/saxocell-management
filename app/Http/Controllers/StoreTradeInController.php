<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Store;
use App\Models\TradeIn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoreTradeInController extends Controller
{
    public function index(Store $store)
    {
        $tradeIns = TradeIn::where('store_id', $store->id)
            ->latest()
            ->paginate(20);

        return view('stores.trade-ins.index', compact('store', 'tradeIns'));
    }

    public function create(Store $store)
    {
        return view('stores.trade-ins.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_contact' => 'nullable|string|max:50',
            'device_name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:100',
            'model' => 'nullable|string|max:100',
            'imei' => 'nullable|string|max:50',
            'condition' => 'required|in:excellent,good,fair,poor',
            'condition_notes' => 'nullable|string',
            'assessed_value' => 'nullable|numeric|min:0',
            'handled_by' => 'nullable|string|max:255',
        ]);

        $validated['store_id'] = $store->id;
        $validated['trade_in_number'] = 'TI-' . strtoupper($store->id . '-' . now()->format('YmdHis'));
        $validated['status'] = 'pending';

        TradeIn::create($validated);

        return redirect()->route('stores.trade-ins.index', $store)
            ->with('success', 'Pengajuan trade-in berhasil disimpan.');
    }

    public function show(Store $store, TradeIn $tradeIn)
    {
        $accounts = FinanceAccount::where('store_id', $store->id)->get();
        return view('stores.trade-ins.show', compact('store', 'tradeIn', 'accounts'));
    }

    public function approve(Request $request, Store $store, TradeIn $tradeIn)
    {
        $request->validate([
            'purchase_price' => 'required|numeric|min:0',
            'handled_by' => 'nullable|string|max:255',
            'finance_account_id' => 'required|exists:finance_accounts,id',
        ]);

        DB::transaction(function () use ($request, $store, $tradeIn) {
            $tradeIn->update([
                'status' => 'approved',
                'purchase_price' => $request->purchase_price,
                'handled_by' => $request->handled_by,
            ]);

            // =====================================================
            // Auto-record Finance Transaction (Pengeluaran / Expense)
            // =====================================================
            $account = FinanceAccount::lockForUpdate()->findOrFail($request->finance_account_id);

            FinanceTransaction::create([
                'finance_account_id' => $account->id,
                'category' => 'Trade-In',
                'title' => 'Trade-In ' . $tradeIn->trade_in_number . ' – ' . $tradeIn->device_name . ' (' . $tradeIn->customer_name . ')',
                'type' => 'expense',
                'amount' => $request->purchase_price,
                'transaction_date' => now()->toDateString(),
                'notes' => 'Kondisi: ' . $tradeIn->condition_label . ($tradeIn->imei ? ' | IMEI: ' . $tradeIn->imei : ''),
                'is_admin_fee' => false,
            ]);

            $account->decrement('balance', $request->purchase_price);
        });

        return redirect()->route('stores.trade-ins.show', [$store, $tradeIn])
            ->with('success', 'Trade-in disetujui dan pengeluaran otomatis dicatat ke keuangan.');
    }

    public function reject(Request $request, Store $store, TradeIn $tradeIn)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string',
        ]);

        $tradeIn->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
        ]);

        return redirect()->route('stores.trade-ins.show', [$store, $tradeIn])
            ->with('success', 'Trade-in ditolak.');
    }
}
