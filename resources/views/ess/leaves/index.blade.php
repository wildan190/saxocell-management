@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-12 pb-24">
        <div class="sm:flex sm:items-center sm:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">My Leave Requests</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Track your time-off applications and
                    approvals.</p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <a href="{{ route('ess.leaves.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-indigo-600 transition-all active:scale-95 uppercase tracking-widest">
                    Request Leave
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Period</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Reason</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($leaves as $leave)
                                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                                <td class="px-8 py-6">
                                                    <p class="font-black text-slate-900">{{ $leave->start_date }} <span
                                                            class="text-slate-300 mx-2">→</span> {{ $leave->end_date }}</p>
                                                </td>
                                                <td class="px-8 py-6 text-sm text-slate-500 max-w-xs truncate font-medium">{{ $leave->reason }}
                                                </td>
                                                <td class="px-8 py-6 text-sm">
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
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-24 text-center text-slate-400 italic">You haven't made any leave
                                    requests yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection