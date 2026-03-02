@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto space-y-12 pb-24">
        <div class="px-4">
            <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">Resignations</h1>
            <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Review and manage employee departure notices.
            </p>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Employee</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Notice Date</th>
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
                        @forelse($resignations as $resignation)
                                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                                <td class="px-8 py-6">
                                                    <p class="font-black text-slate-900">{{ $resignation->user->name }}</p>
                                                </td>
                                                <td class="px-8 py-6 text-sm font-medium text-slate-600">{{ $resignation->resignation_date }}
                                                </td>
                                                <td class="px-8 py-6 text-sm text-slate-500 max-w-xs truncate">{{ $resignation->reason }}</td>
                                                <td class="px-8 py-6">
                                                    @php
                                                        $colors = [
                                                            'pending' => 'bg-amber-50 text-amber-700 ring-amber-600/10',
                                                            'approved' => 'bg-rose-50 text-rose-700 ring-rose-600/10',
                                                            'rejected' => 'bg-slate-50 text-slate-700 ring-slate-600/10',
                                                        ];
                                                    @endphp
                             <span
                                                        class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-black uppercase tracking-widest {{ $colors[$resignation->status] }} ring-1 ring-inset">
                                                        {{ $resignation->status }}
                                                    </span>
                                                </td>
                                                <td class="px-8 py-6 text-right">
                                                    @if($resignation->status === 'pending')
                                                        <div class="flex justify-end gap-2">
                                                            <form action="{{ route('hrm.resignations.approve', $resignation) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="bg-rose-600 text-white px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-rose-500 transition-all font-bold">Approve
                                                                    & Deactivate</button>
                                                            </form>
                                                            <form action="{{ route('hrm.resignations.reject', $resignation) }}" method="POST">
                                                                @csrf
                                                                <button type="submit"
                                                                    class="bg-slate-100 text-slate-400 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest hover:bg-slate-200 transition-all">Reject</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-24 text-center text-slate-400 italic">No resignation records
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection