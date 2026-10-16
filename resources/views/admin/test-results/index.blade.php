{{--
    TEST RESULTS PAGE — all completed TPA test submissions
    $results      → paginated TestResult collection (with user eager loaded)
    $avgScore     → average score across all results
    $highestScore → highest score recorded
    $lowestScore  → lowest score recorded
    $passRate     → % of participants who passed (score >= 60)
--}}
<x-app-layout>
<div class="min-h-screen bg-slate-50 py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


    {{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">

        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-emerald-500 mb-1">Results & Analytics</p>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Test Results</h1>
            <p class="mt-1 text-sm text-slate-500">Review all completed TPA test submissions and participant scores.</p>
        </div>

        <div class="flex items-center gap-3 self-start sm:self-auto">
            <div class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span class="text-sm font-semibold text-slate-700">{{ $results->total() }} Tests Completed</span>
            </div>
        </div>

    </div>


    {{-- ── MINI STATS ROW ───────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @php
            $miniStats = [
                ['label' => 'Avg. Score',    'value' => number_format($avgScore ?? 0, 1), 'unit' => '/100', 'color' => 'text-violet-600'],
                ['label' => 'Highest Score', 'value' => $highestScore ?? 0,               'unit' => '/100', 'color' => 'text-emerald-600'],
                ['label' => 'Lowest Score',  'value' => $lowestScore  ?? 0,               'unit' => '/100', 'color' => 'text-rose-500'],
                ['label' => 'Pass Rate',     'value' => $passRate     ?? 0,               'unit' => '%',    'color' => 'text-amber-600'],
            ];
        @endphp

        @foreach($miniStats as $stat)
            <div class="bg-white rounded-xl border border-slate-200/80 shadow-sm px-5 py-4">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 truncate">{{ $stat['label'] }}</p>
                <p class="mt-0.5 text-2xl font-bold {{ $stat['color'] }} tracking-tight">
                    {{ $stat['value'] }}<span class="text-sm font-medium text-slate-400">{{ $stat['unit'] }}</span>
                </p>
            </div>
        @endforeach
    </div>


    {{-- ── MAIN CARD ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

        {{-- toolbar: search + category filter --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">

            <form action="{{ route('test-results.index') }}" method="GET" class="relative w-full sm:max-w-sm">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                <input
                    type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name…"
                    class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-lg text-slate-700 placeholder-slate-400
                           focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent transition"
                >
            </form>

            {{-- category filter: auto-submits on change, carries other params --}}
            <form action="{{ route('test-results.index') }}" method="GET" class="relative self-start sm:self-auto">
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">

                <select name="category" onchange="this.form.submit()"
                    class="appearance-none bg-white border border-slate-200 text-slate-600 text-sm rounded-lg
                           pl-4 pr-8 py-2 focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent
                           transition cursor-pointer shadow-sm">
                    <option value="">All Categories</option>
                    @foreach(['Verbal', 'Numerik', 'Logika', 'Spasial'] as $cat)
                        <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>

                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center">
                    <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </span>
            </form>

        </div>


        {{-- ── TABLE ───────────────────────────────────────────────── --}}
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">

            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">#</th>

                    @php
                        $cols = [
                            'user_id'    => 'Participant',
                            'created_at' => 'Test Date',
                            'category'   => 'Category',
                            'score'      => 'Score',
                        ];
                    @endphp

                    @foreach($cols as $col => $label)
                        @php
                            $isActive = request('sort') === $col;
                            $nextDir  = ($isActive && request('direction') === 'asc') ? 'desc' : 'asc';
                        @endphp
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <a href="{{ route('test-results.index', array_merge(request()->query(), ['sort' => $col, 'direction' => $nextDir])) }}"
                               class="hover:text-violet-600 {{ $isActive ? 'text-violet-600' : '' }}">
                                {{ $label }}
                                @if($isActive) {{ request('direction') === 'asc' ? '▲' : '▼' }} @endif
                            </a>
                        </th>
                    @endforeach

                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Result</th>
                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            @forelse($results as $index => $result)
                @php
                    $pct  = $result->score;
                    $bar  = $pct >= 75 ? 'bg-emerald-500' : ($pct >= 60 ? 'bg-amber-400' : 'bg-rose-400');
                    $pass = $pct >= 60;

                    $catMap = [
                        'Verbal'  => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                        'Numerik' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                        'Logika'  => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                        'Spasial' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                    ];
                    $catClass = $catMap[$result->category] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20';
                @endphp

                <tr class="hover:bg-violet-50/40 transition-colors duration-100">

                    <td class="px-6 py-4 text-slate-400 text-xs font-medium">
                        {{ $results->firstItem() + $index }}
                    </td>

                    {{-- avatar uses first 2 letters of name, no category dependency --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center uppercase">
                                {{ strtoupper(substr($result->user->name, 0, 2)) }}
                            </span>
                            <span class="font-semibold text-slate-700">{{ $result->user->name }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
                        <p class="text-slate-600 font-medium">{{ $result->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $result->created_at->format('h:i A') }}</p>
                    </td>

                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $catClass }}">
                            {{ $result->category }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2.5">
                            <div class="w-20 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full {{ $bar }} rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <span class="font-bold text-slate-800 tabular-nums">
                                {{ $result->score }}<span class="text-xs font-medium text-slate-400">/100</span>
                            </span>
                        </div>
                    </td>

                    <td class="px-6 py-4">
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
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('test-results.show', $result->id) }}"
                           class="text-violet-600 font-semibold hover:text-violet-800 text-xs">
                            View &rarr;
                        </a>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="7" class="px-6 py-20 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <span class="w-14 h-14 rounded-2xl bg-slate-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </span>
                            <p class="text-sm font-semibold text-slate-600">No results yet</p>
                            <p class="text-xs text-slate-400">Results will appear once participants complete their tests.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>

        </table>
        </div>


        {{-- ── PAGINATION FOOTER ────────────────────────────────────── --}}
        <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-xs text-slate-400 order-2 sm:order-1">
                Showing
                <span class="font-semibold text-slate-600">{{ $results->firstItem() ?? 0 }}</span> to
                <span class="font-semibold text-slate-600">{{ $results->lastItem() ?? 0 }}</span> of
                <span class="font-semibold text-slate-600">{{ $results->total() }}</span> results
            </p>
            <div class="order-1 sm:order-2">
                {{ $results->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
    {{-- END MAIN CARD --}}


</div>
</div>
</x-app-layout>
