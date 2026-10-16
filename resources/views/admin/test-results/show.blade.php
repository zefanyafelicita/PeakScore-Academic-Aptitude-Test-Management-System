{{--
    TEST RESULT DETAIL PAGE — shows one participant's result
    $testResult → the result model (has user, category, score)
    $questions  → the questions that were in this test
--}}
<x-app-layout>
<div class="min-h-screen bg-slate-50 py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


    {{-- ── BACK LINK ────────────────────────────────────────────────── --}}
    <a href="{{ route('test-results.index') }}"
       class="inline-flex items-center text-sm text-slate-500 hover:text-slate-800 mb-6 transition">
        &larr; Back to Results
    </a>


    {{-- ── DETAIL CARD ──────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

        {{-- card header --}}
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/60">
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500 mb-1">Results & Analytics</p>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Test Result Details</h1>
        </div>

        {{-- participant info --}}
        <div class="px-6 py-6 space-y-3 border-b border-slate-100">
            @php
                // category badge colors — same map used everywhere
                $catMap = [
                    'Verbal'  => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                    'Numerik' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                    'Logika'  => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                    'Spasial' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                ];
                $catClass = $catMap[$testResult->category] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20';

                // score bar color: green ≥75, yellow ≥60, red below
                $pct  = $testResult->score;
                $bar  = $pct >= 75 ? 'bg-emerald-500' : ($pct >= 60 ? 'bg-amber-400' : 'bg-rose-400');
                $pass = $pct >= 60;
            @endphp

            <div class="flex items-center gap-2 text-sm text-slate-700">
                <span class="font-semibold w-28 text-slate-400 uppercase tracking-wide text-xs">Participant</span>
                <span class="font-semibold">{{ $testResult->user->name }}</span>
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-700">
                <span class="font-semibold w-28 text-slate-400 uppercase tracking-wide text-xs">Category</span>
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $catClass }}">
                    {{ $testResult->category }}
                </span>
            </div>

            <div class="flex items-center gap-2 text-sm text-slate-700">
                <span class="font-semibold w-28 text-slate-400 uppercase tracking-wide text-xs">Score</span>
                <div class="flex items-center gap-2.5">
                    <div class="w-24 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full {{ $bar }} rounded-full" style="width: {{ $pct }}%"></div>
                    </div>
                    <span class="font-bold text-slate-800">
                        {{ $testResult->score }}<span class="text-xs font-medium text-slate-400">/100</span>
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-2 text-sm">
                <span class="font-semibold w-28 text-slate-400 uppercase tracking-wide text-xs">Result</span>
                @if($pass)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Pass
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-semibold bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-600/20">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Fail
                    </span>
                @endif
            </div>
        </div>

        {{-- questions list --}}
        <div class="px-6 py-6">
            <h2 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4">Questions</h2>
            <ul class="space-y-2">
                @foreach($questions as $i => $question)
                    <li class="flex items-start gap-3 text-sm text-slate-600">
                        {{-- question number bubble --}}
                        <span class="flex-shrink-0 w-6 h-6 rounded-full bg-slate-100 text-slate-500 text-xs font-bold flex items-center justify-center mt-0.5">
                            {{ $i + 1 }}
                        </span>
                        {{ $question->content }}
                    </li>
                @endforeach
            </ul>
        </div>

    </div>
    {{-- END DETAIL CARD --}}


</div>
</div>
</x-app-layout>
