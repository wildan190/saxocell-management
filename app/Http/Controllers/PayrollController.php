<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollController extends Controller
{
    public function index()
    {
        $payrolls = Payroll::latest()->get();
        return view('hrm.payroll.index', compact('payrolls'));
    }

    public function create()
    {
        return view('hrm.payroll.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2024',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
        ]);

        DB::transaction(function () use ($request) {
            $payroll = Payroll::create([
                'month' => $request->month,
                'year' => $request->year,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
                'status' => 'draft',
            ]);

            $employees = Employee::with('user')->get();

            foreach ($employees as $employee) {
                // Calculate attendance-based salary
                $daysPresent = Attendance::where('user_id', $employee->user_id)
                    ->whereBetween('date', [$request->period_start, $request->period_end])
                    ->whereNotNull('clock_in')
                    ->count();

                // Advanced calculation logic
                $basicSalary = $employee->basic_salary;

                // Scale salary based on presence (assuming 22 work days per month)
                $effectiveBasic = ($daysPresent / 22) * $basicSalary;

                $allowance = $employee->allowance ?? 0;

                // Deductions logic
                $deductions = 0;
                if ($employee->tax_pph21)
                    $deductions += $effectiveBasic * 0.05; // 5% PPH21
                if ($employee->jht)
                    $deductions += $effectiveBasic * 0.02;       // 2% JHT
                if ($employee->bpjs)
                    $deductions += $effectiveBasic * 0.01;      // 1% BPJS

                // Overtime bonus
                if ($employee->overtime_eligible && $daysPresent > 20) {
                    $allowance += $basicSalary * 0.1; // 10% bonus for good attendance
                }

                $payroll->items()->create([
                    'user_id' => $employee->user_id,
                    'basic_salary' => $basicSalary,
                    'total_days' => $daysPresent,
                    'allowance' => $allowance,
                    'deductions' => $deductions,
                    'net_salary' => $effectiveBasic + $allowance - $deductions,
                ]);
            }
        });

        return redirect()->route('hrm.payroll.index')->with('success', 'Payroll draft generated.');
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('items.user');
        return view('hrm.payroll.show', compact('payroll'));
    }

    public function publish(Payroll $payroll)
    {
        $payroll->update(['status' => 'published']);
        return back()->with('success', 'Payroll published and payslips are now available for employees.');
    }
}
