<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\PosTransaction;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\TradeIn;
use App\Models\Warehouse;
use App\Models\Inventory;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard with real statistics.
     */
    public function index()
    {
        // General Stats
        $totalStores = Store::count();
        $totalWarehouses = Warehouse::count();
        $totalProducts = \App\Models\Product::count();

        // Financial Stats
        $totalBalance = FinanceAccount::sum('balance');

        // POS Stats (Today)
        $todayPosRevenue = PosTransaction::whereDate('created_at', today())->sum('total_amount');
        $todayPosCount = PosTransaction::whereDate('created_at', today())->count();

        // Trade-In Stats (Pending)
        $pendingTradeIns = TradeIn::where('status', 'pending')->count();

        // Recent Activities
        $recentPosTransactions = PosTransaction::with('store')
            ->latest()
            ->take(5)
            ->get();

        $recentTradeIns = TradeIn::with('store')
            ->latest()
            ->take(5)
            ->get();

        // Critical Stock (Store + Warehouse)
        $lowStockStore = StoreProduct::where('stock', '<=', 5)
            ->with(['store', 'product'])
            ->take(5)
            ->get();

        $lowStockWarehouse = Inventory::where('quantity', '<=', 10)
            ->with(['warehouse', 'product'])
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalStores',
            'totalWarehouses',
            'totalProducts',
            'totalBalance',
            'todayPosRevenue',
            'todayPosCount',
            'pendingTradeIns',
            'recentPosTransactions',
            'recentTradeIns',
            'lowStockStore',
            'lowStockWarehouse'
        ));
    }
}
