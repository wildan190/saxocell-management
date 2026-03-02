@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="sm:flex sm:items-center sm:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">Attendance Log</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium">Monitor daily clock-in and clock-out activities.</p>
            </div>
            <div class="mt-4 sm:mt-0 flex flex-wrap gap-4 items-end">
                <form action="{{ route('hrm.attendance.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 ml-1">By
                            Day</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                            class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 ml-1">By
                            Week</label>
                        <input type="week" name="week" value="{{ request('week') }}"
                            class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 ml-1">By
                            Month</label>
                        <input type="month" name="month" value="{{ request('month') }}"
                            class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1 ml-1">By
                            Year</label>
                        <select name="year"
                            class="rounded-xl border-slate-100 py-2 px-4 shadow-sm focus:ring-indigo-600 focus:border-indigo-600 text-sm">
                            <option value="">All</option>
                            @foreach(range(date('Y'), 2024) as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit"
                        class="bg-slate-900 text-white px-6 py-2.5 rounded-xl font-black uppercase tracking-widest text-xs hover:bg-slate-800 transition-all shadow-lg shadow-slate-200">
                        Filter
                    </button>
                    <a href="{{ route('hrm.attendance.index') }}"
                        class="text-xs font-black uppercase tracking-widest text-slate-400 hover:text-slate-600 pb-3">Clear</a>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Employee</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Date</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Clock In</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Clock Out</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Location</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($attendances as $attendance)
                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs">
                                            {{ substr($attendance->user->name, 0, 1) }}
                                        </div>
                                        <p class="font-black text-slate-900">{{ $attendance->user->name }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-6 font-medium text-slate-600">{{ $attendance->date }}</td>
                                <td class="px-8 py-6">
                                    <span
                                        class="inline-flex items-center rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-black text-emerald-700 ring-1 ring-inset ring-emerald-600/10">
                                        {{ $attendance->clock_in ?? '--:--' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6">
                                    <span
                                        class="inline-flex items-center rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-black text-rose-700 ring-1 ring-inset ring-rose-600/10">
                                        {{ $attendance->clock_out ?? '--:--' }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                    {{ $attendance->location_in ?? 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center text-slate-400 italic">No attendance records found
                                    for this date.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-8 py-6 border-t border-slate-100">
                {{ $attendances->links() }}
            </div>
        </div>
    </div>
@endsection