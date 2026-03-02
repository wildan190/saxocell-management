<?php

namespace App\Http\Controllers;

use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResignationController extends Controller
{
    public function index()
    {
        $resignations = Resignation::with('user')->latest()->get();
        return view('hrm.resignations.index', compact('resignations'));
    }

    public function approve(Resignation $resignation)
    {
        $resignation->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
        ]);

        // Deactivate user account
        $resignation->user->update(['is_active' => false]);

        return back()->with('success', 'Resignation approved and account deactivated.');
    }

    public function reject(Resignation $resignation)
    {
        $resignation->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
        ]);

        return back()->with('success', 'Resignation rejected.');
    }
}
