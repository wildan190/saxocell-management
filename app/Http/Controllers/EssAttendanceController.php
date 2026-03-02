<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EssAttendanceController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $todayAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        return view('ess.dashboard', compact('todayAttendance'));
    }

    public function clockIn(Request $request)
    {
        $user = Auth::user();

        $attendance = Attendance::firstOrCreate(
            ['user_id' => $user->id, 'date' => now()->toDateString()],
            ['clock_in' => now()->toTimeString(), 'location_in' => $request->location ?? 'Office']
        );

        return back()->with('success', 'Clocked in successfully at ' . $attendance->clock_in);
    }

    public function clockOut(Request $request)
    {
        $user = Auth::user();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', now()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->update([
                'clock_out' => now()->toTimeString(),
                'location_out' => $request->location ?? 'Office'
            ]);
            return back()->with('success', 'Clocked out successfully at ' . $attendance->clock_out);
        }

        return back()->with('error', 'You haven\'t clocked in today.');
    }
}
