@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto space-y-12 pb-24">
        <div class="sm:flex sm:items-center sm:justify-between px-4">
            <div>
                <h1 class="text-4xl font-black leading-tight tracking-tight text-slate-900">Resignation</h1>
                <p class="mt-2 text-sm text-slate-500 font-medium tracking-tight">Submit your official resignation notice.
                </p>
            </div>
            <div class="mt-4 sm:ml-16 sm:mt-0">
                <a href="{{ route('ess.resignations.create') }}"
                    class="inline-flex items-center justify-center rounded-2xl bg-slate-900 px-8 py-4 text-sm font-black text-white shadow-xl hover:bg-rose-600 transition-all active:scale-95 uppercase tracking-widest">
                    Submit Resignation
                </a>
            </div>
        </div>

        <div class="bg-white rounded-[40px] shadow-sm ring-1 ring-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-slate-50/50 border-b border-slate-100">
                        <tr>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Notice Date</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Status</th>
                            <th scope="col" class="px-8 py-6 text-xs font-black uppercase tracking-[0.2em] text-slate-400">
                                Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($resignations as $resignation)
                                            <tr class="group hover:bg-slate-50/50 transition-colors">
                                                <td class="px-8 py-6 font-black text-slate-900">{{ $resignation->resignation_date }}</td>
                                                <td class="px-8 py-6 text-sm">
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
                                                <td class="px-8 py-6 text-sm text-slate-500 font-medium truncate max-w-xs">
                                                    {{ $resignation->reason }}</td>
                                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-24 text-center text-slate-400 italic">No resignation records
                                    found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection