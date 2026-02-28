<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Warehouse;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FinanceTransactionController extends Controller
{
    /**
     * Display a listing of finance transactions for a specific warehouse or store.
     */
    public function index(Warehouse $warehouse = null, Store $store = null)
    {
        $owner = $warehouse ?? $store;
        $ownerType = $warehouse ? 'warehouse_id' : 'store_id';

        $transactions = FinanceTransaction::whereHas('account', function ($query) use ($owner, $ownerType) {
            $query->where($ownerType, $owner->id);
        })
            ->with(['account'])
            ->latest()
            ->get();

        $accounts = $owner->financeAccounts;
        $allWarehouses = Warehouse::with('financeAccounts')->get();
        $allStores = Store::with('financeAccounts')->get();
        $allOwners = $allWarehouses->concat($allStores);
        $suppliers = \App\Models\Supplier::all();

        return view('finance.transactions.index', compact('transactions', 'owner', 'accounts', 'allOwners', 'suppliers'));
    }

    /**
     * Store a newly created transaction.
     */
    public function store(Request $request, Warehouse $warehouse = null, Store $store = null)
    {
        $owner = $warehouse ?? $store;
        $validated = $request->validate([
            'category' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'is_admin_fee' => 'nullable|boolean',
            'transaction_date' => 'required|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'supplier_id' => 'nullable|exists:suppliers,id',

            // Allow either single account/amount or splits array
            'finance_account_id' => 'required_without:splits|exists:finance_accounts,id',
            'amount' => 'required_without:splits|numeric|min:0',

            // Splits validation
            'splits' => 'required_without:finance_account_id|array',
            'splits.*.finance_account_id' => 'required_with:splits|exists:finance_accounts,id',
            'splits.*.amount' => 'required_with:splits|numeric|min:0.01',
            'splits.*.is_admin_fee' => 'nullable',
        ]);

        $isAdminFee = $request->has('is_admin_fee') && $request->is_admin_fee;

        DB::transaction(function () use ($validated, $request) {
            $splits = [];

            if (isset($validated['splits']) && count($validated['splits']) > 0) {
                foreach ($validated['splits'] as $split) {
                    // 1. Record the main transaction split
                    $splits[] = [
                        'finance_account_id' => $split['finance_account_id'],
                        'amount' => $split['amount'],
                        'is_admin_fee' => false,
                        'title' => $validated['title'],
                        'category' => $validated['category'],
                    ];

                    // 2. Record the separate Admin Fee if checked
                    if (isset($split['is_admin_fee']) && $split['is_admin_fee']) {
                        $splits[] = [
                            'finance_account_id' => $split['finance_account_id'],
                            'amount' => 2500,
                            'is_admin_fee' => true,
                            'title' => 'Admin Fee - ' . $validated['title'],
                            'category' => 'Biaya Admin',
                        ];
                    }
                }
            } else {
                $isAdminFee = $request->has('is_admin_fee') && $request->is_admin_fee;

                // 1. Main record
                $splits[] = [
                    'finance_account_id' => $validated['finance_account_id'],
                    'amount' => $validated['amount'],
                    'is_admin_fee' => false,
                    'title' => $validated['title'],
                    'category' => $validated['category'],
                ];

                // 2. Separate Admin Fee record
                if ($isAdminFee) {
                    $splits[] = [
                        'finance_account_id' => $validated['finance_account_id'],
                        'amount' => 2500,
                        'is_admin_fee' => true,
                        'title' => 'Admin Fee - ' . $validated['title'],
                        'category' => 'Biaya Admin',
                    ];
                }
            }

            foreach ($splits as $splitData) {
                // Prepare final data including possible supplier_id and other fields
                $transactionRecord = array_merge($validated, [
                    'finance_account_id' => $splitData['finance_account_id'],
                    'amount' => $splitData['amount'],
                    'is_admin_fee' => $splitData['is_admin_fee'],
                    'title' => $splitData['title'],
                    'category' => $splitData['category'],
                ]);

                // Remove splits array from data to be inserted
                unset($transactionRecord['splits']);

                FinanceTransaction::create($transactionRecord);
                $account = FinanceAccount::find($splitData['finance_account_id']);

                if ($validated['type'] === 'income') {
                    $account->increment('balance', $splitData['amount']);
                } else {
                    $account->decrement('balance', $splitData['amount']);
                }
            }
        });

        $route = $warehouse ? 'finance.transactions.index' : 'stores.finance.transactions.index';
        return redirect()->route($route, $owner)
            ->with('success', 'Transaction recorded successfully.');
    }

    /**
     * Handle internal balance transfer between two accounts.
     */
    public function transfer(Request $request, Warehouse $warehouse = null, Store $store = null)
    {
        $owner = $warehouse ?? $store;
        $request->validate([
            'from_account_id' => 'required|exists:finance_accounts,id',
            'to_account_id' => 'required|exists:finance_accounts,id|different:from_account_id',
            'amount' => 'required|numeric|min:0.01',
            'transfer_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $fromAccount = FinanceAccount::with(['warehouse', 'store'])->findOrFail($request->from_account_id);
        $toAccount = FinanceAccount::with(['warehouse', 'store'])->findOrFail($request->to_account_id);

        if ($fromAccount->balance < $request->amount) {
            return redirect()->back()->withErrors(['amount' => 'Insufficient balance in source account.']);
        }

        DB::transaction(function () use ($request, $fromAccount, $toAccount) {
            $fromOwnerName = $fromAccount->warehouse ? $fromAccount->warehouse->name : $fromAccount->store->name;
            $toOwnerName = $toAccount->warehouse ? $toAccount->warehouse->name : $toAccount->store->name;

            $isCrossLocation = ($fromAccount->warehouse_id !== $toAccount->warehouse_id) || ($fromAccount->store_id !== $toAccount->store_id);

            $fromTitle = 'Transfer to ' . $toAccount->name;
            $toTitle = 'Transfer from ' . $fromAccount->name;

            if ($isCrossLocation) {
                $fromTitle .= " ({$toOwnerName})";
                $toTitle .= " ({{$fromOwnerName}})";
            }

            // Debit from source
            FinanceTransaction::create([
                'finance_account_id' => $fromAccount->id,
                'category' => 'Transfer Keluar',
                'title' => $fromTitle,
                'amount' => $request->amount,
                'type' => 'expense',
                'transaction_date' => $request->transfer_date,
                'notes' => $request->notes,
            ]);
            $fromAccount->decrement('balance', $request->amount);

            // Credit to destination
            FinanceTransaction::create([
                'finance_account_id' => $toAccount->id,
                'category' => 'Transfer Masuk',
                'title' => $toTitle,
                'amount' => $request->amount,
                'type' => 'income',
                'transaction_date' => $request->transfer_date,
                'notes' => $request->notes,
            ]);
            $toAccount->increment('balance', $request->amount);
        });

        $route = $warehouse ? 'finance.transactions.index' : 'stores.finance.transactions.index';
        return redirect()->route($route, $owner)
            ->with('success', 'Balance transferred successfully.');
    }
}
