<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with('user')->latest()->get();
        return view('hrm.employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::where('name', '!=', 'owner')->pluck('name', 'name');
        return view('hrm.employees.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'employee_id' => 'required|string|unique:employees',
            'position_role' => 'required|string|exists:roles,name',
            'basic_salary' => 'required|numeric|min:0',
            'allowance' => 'nullable|numeric|min:0',
            'overtime_rate' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->position_role,
                'is_active' => true,
            ]);

            $user->assignRole($request->position_role);

            $user->employee()->create([
                'employee_id' => $request->employee_id,
                'basic_salary' => $request->basic_salary,
                'allowance' => $request->allowance,
                'tax_pph21' => $request->has('tax_pph21'),
                'jht' => $request->has('jht'),
                'bpjs' => $request->has('bpjs'),
                'overtime_eligible' => $request->has('overtime_eligible'),
                'overtime_rate' => $request->overtime_rate ?? 0,
                'onboarded_at' => now(),
            ]);
        });

        return redirect()->route('hrm.employees.index')
            ->with('success', 'Employee onboarded successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user->email === 'owner@saxocell.id') {
            return redirect()->route('hrm.employees.index')
                ->with('error', 'The owner account cannot be deactivated or deleted.');
        }

        DB::transaction(function () use ($employee) {
            $user = $employee->user;
            $user->is_active = false;
            $user->save();

            // Optionally $employee->delete() if soft deletes, but deactivating user is enough based on request.
        });

        return redirect()->route('hrm.employees.index')
            ->with('success', 'Employee deactivated successfully.');
    }
}
