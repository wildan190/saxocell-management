@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="px-4">
            <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">Leave Requests</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium">Review and approve employee time-off requests.</p>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Employee</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Period</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Reason</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Status</th>
                            <th scope="col"
                                class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400 text-right">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leaves as $leave)
                                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                                <td class="px-8 py-6">
                                                    <p class="font-black text-slate-900">{{ $leave->user->name }}</p>
                                                </td>
                                                <td class="px-8 py-6 text-sm font-medium text-slate-600">
                                                    {{ $leave->start_date }} <span class="text-slate-300 mx-2">→</span> {{ $leave->end_date }}
                                                </td>
                                                <td class="px-8 py-6 text-sm text-slate-500 max-w-xs truncate">{{ $leave->reason }}</td>
                                                <td class="px-8 py-6">
                                                    @php
                                                        $colors = [
                                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                                            'approved' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
                                                            'rejected' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                                        ];
                                                    @endphp
                             <span
                                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-black uppercase tracking-widest {{ $colors[$leave->status] }} ring-1 ring-inset">
                                                        {{ $leave->status }}
                                                    </span>
                                                </td>
                                                <td class="px-8 py-6 text-right">
                                                    @if($leave->status === 'pending')
                                                        <div class="flex justify-end gap-2">
                                                            <form action="{{ route('hrm.leaves.approve', $leave) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-emerald-500 transition-all">Approve</button>
                                                            </form>
                                                            <form action="{{ route('hrm.leaves.reject', $leave) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="bg-rose-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-500 transition-all">Reject</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center text-slate-400 italic">No leave requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection