<x-app-layout>

    {{-- 1. PRIMARY NAVIGATION --}}
    <div class="min-h-screen bg-slate-100 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- 1. WELCOME BANNER --}}
            <div class="relative bg-gradient-to-br from-[#1c1f23] via-[#1c1f23] to-[#2e3238] rounded-2xl shadow-xl overflow-hidden px-6 sm:px-10 py-9">
                {{-- Background circle shapes --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -translate-y-1/3 translate-x-1/3 pointer-events-none"></div>
                <div class="absolute bottom-0 left-20 w-40 h-40 bg-white/5 rounded-full translate-y-1/2 pointer-events-none"></div>

                <div class="relative flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
                    <div>
                        <p class="text-gray-400 text-sm font-medium mb-1">{{ now()->format('l, d F Y') }}</p>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">
                            Welcome back, {{ auth()->user()->name }}! 👋
                        </h1>
                        <p class="mt-2 text-gray-300/80 text-sm max-w-md leading-relaxed">
                            Here's an overview of the TPA Management System. Monitor questions, participants, and test activity at a glance.
                        </p>
                    </div>

                    {{-- Admin-only action --}}
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('questions.create') }}"
                           class="inline-flex items-center gap-2 bg-white hover:bg-gray-100 text-[#1c1f23] text-sm font-semibold px-5 py-3 rounded-xl shadow-md transition-all duration-150 whitespace-nowrap self-start sm:self-auto">
                            <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                            </svg>
                            Add Question
                        </a>
                    @endif
                </div>
            </div>

            {{-- 2. STATS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">

                {{-- Card 1: Total Questions --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 flex items-center gap-5 hover:shadow-md transition-shadow duration-200">
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center">
                        <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-0.5">Total Questions</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">{{ $totalQuestions }}</p>
                    </div>
                </div>

                {{-- Card 2: Registered Participants --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 flex items-center gap-5 hover:shadow-md transition-shadow duration-200">
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center">
                        <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-0.5">Registered Participants</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">{{ $totalParticipants }}</p>
                    </div>
                </div>

                {{-- Card 3: Tests Completed --}}
                <div class="bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6 flex items-center gap-5 hover:shadow-md transition-shadow duration-200">
                    <div class="flex-shrink-0 w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center">
                        <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-0.5">Tests Completed</p>
                        <p class="text-3xl font-bold text-slate-800 tracking-tight">{{ $testsCompleted }}</p>
                    </div>
                </div>
            </div>

            {{-- 3. LOWER ROW: Navigation & Category Analysis --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Quick Navigation Links (Hover diubah menjadi Solid Charcoal) --}}
                <div class="lg:col-span-1 bg-white rounded-2xl border border-slate-200/60 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 mb-4">Quick Navigation</h3>
                    <nav class="space-y-2">
                        <a href="{{ route('questions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 border border-slate-100/70 hover:bg-[#1c1f23] hover:border-[#1c1f23] hover:text-white text-slate-600 text-sm font-medium transition duration-150">
                            Question Bank
                        </a>
                        <a href="{{ route('test-results.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 border border-slate-100/70 hover:bg-[#1c1f23] hover:border-[#1c1f23] hover:text-white text-slate-600 text-sm font-medium transition duration-150">
                            Test Results
                        </a>
                        @if(auth()->user()->is_admin)
                            <a href="{{ route('questions.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 border border-slate-100/70 hover:bg-[#1c1f23] hover:border-[#1c1f23] hover:text-white text-slate-600 text-sm font-medium transition duration-150">
                                New Question
                            </a>
                        @endif
                    </nav>
                </div>

                {{-- Question Distribution by Category --}}
                <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 mb-5">Questions by Category</h3>
                    <div class="space-y-4">
                        @php
                            $cats = [
                                ['label' => 'Verbal',  'count' => $verbalCount,  'color' => 'bg-blue-500',   'text' => 'text-blue-600',   'bg' => 'bg-blue-50'],
                                ['label' => 'Numerik', 'count' => $numerikCount, 'color' => 'bg-amber-500',  'text' => 'text-amber-600',  'bg' => 'bg-amber-50'],
                                ['label' => 'Logika',  'count' => $logikaCount,  'color' => 'bg-purple-500', 'text' => 'text-purple-600', 'bg' => 'bg-purple-50'],
                                ['label' => 'Spasial', 'count' => $spasialCount, 'color' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
                            ];
                        @endphp

                        @foreach($cats as $cat)
                            <div>
                                <div class="flex items-center justify-between mb-1.5">
                                    <span class="inline-flex px-2 py-0.5 rounded-md text-xs font-semibold {{ $cat['bg'] }} {{ $cat['text'] }}">
                                        {{ $cat['label'] }}
                                    </span>
                                    <span class="text-xs font-semibold text-slate-500">{{ $cat['count'] }} / {{ $totalQuestions }}</span>
                                </div>
                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full {{ $cat['color'] }} rounded-full transition-all duration-500"
                                         style="width: {{ $totalQuestions > 0 ? ($cat['count'] / $totalQuestions) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
