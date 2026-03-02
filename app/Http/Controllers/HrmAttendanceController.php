<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;

class HrmAttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Attendance::with('user')->latest();

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        if ($request->filled('week')) {
            $date = \Carbon\Carbon::parse($request->week);
            $query->whereBetween('date', [$date->startOfWeek()->toDateString(), $date->endOfWeek()->toDateString()]);
        }

        if ($request->filled('month')) {
            $date = \Carbon\Carbon::parse($request->month . '-01');
            $query->whereMonth('date', $date->month)
                ->whereYear('date', $date->year);
        }

        if ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        }

        $attendances = $query->paginate(20);

        return view('hrm.attendance.index', compact('attendances'));
    }
}
