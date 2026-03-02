<?php

namespace App\Http\Controllers;

use App\Models\Resignation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssResignationController extends Controller
{
    public function index()
    {
        $resignations = Resignation::where('user_id', Auth::id())->latest()->get();
        return view('ess.resignations.index', compact('resignations'));
    }

    public function create()
    {
        return view('ess.resignations.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'resignation_date' => 'required|date|after:today',
            'reason' => 'required|string',
        ]);

        Resignation::create([
            'user_id' => Auth::id(),
            'resignation_date' => $request->resignation_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('ess.resignations.index')->with('success', 'Resignation request submitted.');
    }
}
