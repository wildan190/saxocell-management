<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssLeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', Auth::id())->latest()->get();
        return view('ess.leaves.index', compact('leaves'));
    }

    public function create()
    {
        return view('ess.leaves.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        Leave::create([
            'user_id' => Auth::id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('ess.leaves.index')->with('success', 'Leave request submitted.');
    }
}
