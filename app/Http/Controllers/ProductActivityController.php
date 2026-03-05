<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductActivity;
use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProductActivityController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'activity_type' => 'required|in:repair,improvement,status_change,other,price_adjustment',
            'description' => 'required|string',
            'cost' => 'nullable|numeric|min:0',
            'finance_account_id' => 'required_if:cost,>0|nullable|exists:finance_accounts,id',
        ]);

        return DB::transaction(function () use ($request, $product) {
            $financeTransactionId = null;

            // Handle Finance Integration if there's a cost
            if ($request->cost > 0) {
                $account = FinanceAccount::findOrFail($request->finance_account_id);

                $transaction = FinanceTransaction::create([
                    'finance_account_id' => $account->id,
                    'type' => 'out',
                    'category' => 'Product Activity',
                    'title' => 'Biaya ' . ucfirst($request->activity_type) . ': ' . $product->name,
                    'amount' => $request->cost,
                    'description' => $request->description,
                    'transaction_date' => now(),
                ]);

                // Update Account Balance
                $account->decrement('balance', $request->cost);
                $financeTransactionId = $transaction->id;
            }

            // Create Activity Log
            $product->activities()->create([
                'user_id' => Auth::id(),
                'activity_type' => $request->activity_type,
                'description' => $request->description,
                'cost' => $request->cost ?? 0,
                'finance_transaction_id' => $financeTransactionId,
            ]);

            // Auto-update Product Status
            if ($request->activity_type === 'repair') {
                $product->update(['status' => 'service']);
            } elseif ($request->activity_type === 'status_change') {
                // If user manually recorded a status change, we check description or add a new field
                // For now, let's keep it simple: if it's status_change, maybe they meant 'available'
                if (str_contains(strtolower($request->description), 'siap') || str_contains(strtolower($request->description), 'available')) {
                    $product->update(['status' => 'available']);
                }
            }

            return back()->with('success', 'Aktivitas produk berhasil dicatat.');
        });
    }
}
