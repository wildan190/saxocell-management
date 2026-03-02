<?php

namespace App\Http\Controllers;

use App\Models\Payroll;
use App\Models\PayrollItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssPayrollController extends Controller
{
    public function index()
    {
        $payrollItems = PayrollItem::with('payroll')
            ->where('user_id', Auth::id())
            ->whereHas('payroll', function ($q) {
                $q->where('status', 'published');
            })
            ->latest()
            ->get();

        return view('ess.payroll.index', compact('payrollItems'));
    }

    public function show(PayrollItem $payrollItem)
    {
        if ($payrollItem->user_id !== Auth::id()) {
            abort(403);
        }

        return view('ess.payroll.show', compact('payrollItem'));
    }
}
