<x-app-layout>
    {{-- Main Container --}}
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Page Header: Displays user-specific greeting and purpose --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">My Test History</h1>
                <p class="mt-1 text-sm text-slate-500">Welcome back, {{ auth()->user()->name }}! Review your completed TPA exam scores here.</p>
            </div>

            {{-- Exam Launcher: Quick-access buttons for the user to start a new test --}}
            <div class="mb-8 p-6 bg-white rounded-2xl shadow-sm border border-slate-200/80">
                <h2 class="text-lg font-bold text-slate-800 mb-4">Start New Exam</h2>
                <div class="flex gap-4">
                    @php
                        $colorMapping = [
                            'Verbal'  => 'bg-blue-600 text-white hover:bg-blue-700 shadow-sm shadow-blue-100',
                            'Numerik' => 'bg-amber-500 text-white hover:bg-amber-600 shadow-sm shadow-amber-100',
                            'Logika'  => 'bg-violet-600 text-white hover:bg-violet-700 shadow-sm shadow-violet-100',
                            'Spasial' => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-100',
                        ];
                    @endphp
                    @foreach(['Verbal', 'Numerik', 'Logika', 'Spasial'] as $cat)
                        <a href="{{ route('test.take', $cat) }}"
                        class="px-4 py-2 {{ $colorMapping[$cat] ?? 'bg-slate-50 text-slate-700 border-slate-200' }} border text-sm font-semibold rounded-lg transition duration-150">
                            {{ $cat }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Results Section: Logic toggles between Empty State and Data Table --}}
            @if($myResults->isEmpty())
                {{-- Shown if user has no test records yet --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-12 text-center">
                    <div class="w-16 h-16 bg-violet-50 text-violet-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700 mb-1">No Exam Records Found</h3>
                    <p class="text-sm text-slate-400 max-w-sm mx-auto">Complete your first TPA exam to see your scores here.</p>
                </div>
            @else
                {{-- Data Table: Renders all test attempts saved in the database --}}
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50">
                            <tr class="border-b border-slate-100">
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Test Date</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Category</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Score</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($myResults as $result)
                                @php
                                    // Logic for dynamic styling: determining colors based on score
                                    $pass = $result->score >= 60;
                                    $barColor = $result->score >= 75 ? 'bg-emerald-500' : ($pass ? 'bg-amber-400' : 'bg-rose-400');

                                    // Match function to color-code categories for better UI scanning
                                    $catColor = match($result->category) {
                                        'Verbal'  => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                        'Numerik' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                        'Logika'  => 'bg-violet-50 text-violet-700 ring-violet-600/20',
                                        'Spasial' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                        default   => 'bg-slate-50 text-slate-700 ring-slate-600/20',
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/50 transition">
                                    {{-- Display formatted date --}}
                                    <td class="px-6 py-4 font-medium text-slate-600">{{ $result->created_at?->format('d M Y') ?? date('d M Y') }}</td>

                                    {{-- Display category with color-coded badge --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{ $catColor }}">
                                            {{ $result->category ?? 'General' }}
                                        </span>
                                    </td>

                                    {{-- Display score progress bar and text --}}
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full {{ $barColor }}" style="width: {{ $result->score }}%"></div>
                                            </div>
                                            <span class="font-bold text-slate-800 tabular-nums">{{ $result->score }}/100</span>
                                        </div>
                                    </td>

                                    {{-- Pass/Fail status badge --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium {{ $pass ? 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' : 'bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-600/20' }}">
                                            {{ $pass ? 'Pass' : 'Fail' }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
