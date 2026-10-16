{{--
    PARTICIPANT PROFILE PAGE :>
    - Details view for individual accounts
    - Receives $participant instance containing pre-loaded histories
--}}
<x-app-layout>
<div class="min-h-screen bg-slate-50 py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- ── BACK BUTTON & PAGE HEADER ───────────────────────────────── --}}
    <div class="mb-8">
        <a href="{{ route('participants.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-violet-600 transition mb-4 group">
            <svg class="w-4 h-4 transform group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Participants
        </a>

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-1">Participant Profile</p>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">{{ $participant->name }}</h1>
                <p class="mt-1 text-sm text-slate-500">Manage account information and view historical TPA test results.</p>
            </div>

            {{-- Account Status Badge --}}
            <div class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-sm self-start sm:self-auto">
                <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                <span class="text-sm font-semibold text-slate-700">Active Account</span>
            </div>
        </div>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- ── LEFT COLUMN: USER META CARD ──────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden lg:col-span-1">
            <div class="p-6 text-center border-b border-slate-100 bg-slate-50/40">
                {{-- Dynamic Initials Avatar --}}
                <div class="w-20 h-20 mx-auto rounded-full bg-violet-100 text-violet-700 text-2xl font-bold flex items-center justify-center uppercase shadow-inner mb-4">
                    {{ strtoupper(substr($participant->name, 0, 2)) }}
                </div>
                <h3 class="font-bold text-lg text-slate-800">{{ $participant->name }}</h3>
                <p class="text-xs text-slate-400 mt-0.5">Joined on {{ $participant->created_at?->format('d M Y') ?? '-' }}</p>
            </div>

            <div class="p-6 space-y-4">
                <div>
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Email Address</label>
                    <p class="text-sm font-medium text-slate-700 break-all">{{ $participant->email }}</p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Account ID</label>
                    <p class="text-sm font-mono text-slate-600">#{{ $participant->id }}</p>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-400 uppercase tracking-wider block mb-1">Total Tests Attempted</label>
                    <span class="mt-1 inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-violet-50 text-violet-700 border border-violet-100">
                        @php $count = $participant->test_results_count ?? $participant->testResults->count(); @endphp
                        {{ $count }} {{ Str::plural('test', $count) }}
                    </span>
                </div>
            </div>
        </div>


        {{-- ── RIGHT COLUMN: TEST HISTORY ────────────────────────────── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden lg:col-span-2">

            <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex items-center justify-between">
                <h2 class="font-bold text-slate-800 text-base tracking-tight">Test History</h2>
                <span class="text-xs font-medium text-slate-400">Chronological order</span>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Test Name</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Date Taken</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        {{-- Assuming a 'testResults' relationship exists on your User model --}}
                        @forelse($participant->testResults as $result)
                            <tr class="hover:bg-violet-50/30 transition-colors duration-100">
                                <td class="px-6 py-4 font-medium text-slate-700">
                                    {{ $result->test_name ?? 'TPA Standard Examination' }}
                                </td>
                                <td class="px-6 py-4 text-slate-500 text-xs">
                                    {{ $result->created_at?->format('d M Y, H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 font-mono font-bold text-sm text-slate-800">
                                    {{ $result->score ?? 'N/A' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if(($result->score ?? 0) >= 500) {{-- Custom TPA threshold logic example --}}
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            Passed
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                            Completed
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center text-slate-400 italic text-sm">
                                    This participant has not taken any tests yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>

</div>
</div>
</x-app-layout>
