<?php

namespace App\Http\Controllers;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Warehouse;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class FinanceReportController extends Controller
{
    public function index()
    {
        $totalBalance = FinanceAccount::sum('balance');
        $monthlyIncome = FinanceTransaction::where('type', 'income')
            ->whereMonth('transaction_date', date('m'))
            ->whereYear('transaction_date', date('Y'))
            ->sum('amount');
        $monthlyExpense = FinanceTransaction::where('type', 'expense')
            ->whereMonth('transaction_date', date('m'))
            ->whereYear('transaction_date', date('Y'))
            ->sum('amount');

        return view('finance.reports.index', compact('totalBalance', 'monthlyIncome', 'monthlyExpense'));
    }

    public function ledger(Request $request)
    {
        $accounts = FinanceAccount::with(['warehouse', 'store'])->get();
        $selectedAccountId = $request->get('account_id');

        $query = FinanceTransaction::with('account');

        if ($selectedAccountId) {
            $query->where('finance_account_id', $selectedAccountId);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date, $request->end_date]);
        }

        $transactions = $query->latest('transaction_date')->latest('id')->paginate(50);

        return view('finance.reports.ledger', compact('accounts', 'transactions', 'selectedAccountId'));
    }

    public function cashflow(Request $request)
    {
        $year = $request->get('year', date('Y'));

        $cashflow = FinanceTransaction::select(
            DB::raw('CAST(strftime("%m", transaction_date) AS INTEGER) as month'),
            DB::raw('SUM(CASE WHEN type = "income" THEN amount ELSE 0 END) as income'),
            DB::raw('SUM(CASE WHEN type = "expense" THEN amount ELSE 0 END) as expense')
        )
            ->whereYear('transaction_date', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month');

        return view('finance.reports.cashflow', compact('cashflow', 'year'));
    }

    public function neraca()
    {
        $warehouseAccounts = FinanceAccount::whereNotNull('warehouse_id')->with('warehouse')->get();
        $storeAccounts = FinanceAccount::whereNotNull('store_id')->with('store')->get();

        $totalAssets = FinanceAccount::sum('balance');

        return view('finance.reports.neraca', compact('warehouseAccounts', 'storeAccounts', 'totalAssets'));
    }

    public function pnl(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $revenue = FinanceTransaction::where('type', 'income')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $expenses = FinanceTransaction::where('type', 'expense')
            ->whereBetween('transaction_date', [$startDate, $endDate])
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        $totalRevenue = $revenue->sum('total');
        $totalExpenses = $expenses->sum('total');
        $netProfit = $totalRevenue - $totalExpenses;

        return view('finance.reports.pnl', compact('revenue', 'expenses', 'totalRevenue', 'totalExpenses', 'netProfit', 'startDate', 'endDate'));
    }
}
