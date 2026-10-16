{{--
    PARTICIPANTS PAGE — lists all registered (non-admin) users
    $participants → paginated User collection
    $sortBy       → active sort column
    $sortDir      → active sort direction (asc/desc)
--}}
<x-app-layout>
<div class="min-h-screen bg-slate-50 py-10">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


    {{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">

        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-1">User Management</p>
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Participants</h1>
            <p class="mt-1 text-sm text-slate-500">All registered TPA test participants and their account details.</p>
        </div>

        {{-- total count badge --}}
        <div class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-xl px-4 py-2.5 shadow-sm self-start sm:self-auto">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span class="text-sm font-semibold text-slate-700">
                {{ $participants->total() }} Registered
            </span>
        </div>

    </div>


    {{-- ── MAIN CARD ────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

        {{-- toolbar: search + export --}}
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">

            <form action="{{ route('participants.index') }}" method="GET" class="relative w-full sm:max-w-sm">
                {{-- carry over sort so search doesn't reset it --}}
                <input type="hidden" name="sort" value="{{ $sortBy }}">
                <input type="hidden" name="direction" value="{{ $sortDir }}">
                <span class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                    </svg>
                </span>
                <input
                    type="text" name="search"
                    value="{{ request('search') }}"
                    placeholder="Search by name or email…"
                    class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-slate-200 rounded-lg text-slate-700 placeholder-slate-400
                           focus:outline-none focus:ring-2 focus:ring-violet-400 focus:border-transparent transition"
                >
            </form>

            <a href="{{ route('participants.export', request()->query()) }}"
               class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-medium
                      px-4 py-2 rounded-lg transition shadow-sm self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                </svg>
                Export CSV
            </a>

        </div>


        {{-- ── TABLE ───────────────────────────────────────────────── --}}
        <div class="overflow-x-auto">
        <table class="min-w-full text-sm">

            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">#</th>

                    {{-- sortable columns --}}
                    @php
                        $cols = [
                            'name'               => 'Name',
                            'email'              => 'Email',
                            'test_results_count' => 'Tests Taken',
                            'created_at'         => 'Joined',
                        ];
                    @endphp

                    @foreach($cols as $col => $label)
                        @php
                            $isActive  = $sortBy === $col;
                            $nextDir   = ($isActive && $sortDir === 'asc') ? 'desc' : 'asc';
                        @endphp
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <a href="{{ route('participants.index', array_merge(request()->query(), ['sort' => $col, 'direction' => $nextDir])) }}"
                               class="hover:text-violet-600 {{ $isActive ? 'text-violet-600' : '' }}">
                                {{ $label }}
                                @if($isActive)
                                    {{ $sortDir === 'asc' ? '▲' : '▼' }}
                                @endif
                            </a>
                        </th>
                    @endforeach

                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
            @forelse($participants as $index => $p)

                <tr class="hover:bg-violet-50/40 transition-colors duration-100">

                    <td class="px-6 py-4 text-slate-400 text-xs font-medium">
                        {{ $participants->firstItem() + $index }}
                    </td>

                    {{-- User profiles: displays dynamic initials placeholder + name --}}
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 w-8 h-8 rounded-full bg-violet-100 text-violet-700 text-xs font-bold flex items-center justify-center uppercase">
                                {{ strtoupper(substr($p->name, 0, 2)) }}
                            </span>
                            <span class="font-medium text-slate-700">{{ $p->name }}</span>
                        </div>
                    </td>

                    <td class="px-6 py-4 text-slate-500">{{ $p->email }}</td>

                    {{-- test count badge --}}
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">
                            {{ $p->test_results_count }} tests
                        </span>
                    </td>

                    <td class="px-6 py-4 text-slate-400 text-xs">
                        {{ $p->created_at?->format('d M Y') ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('participants.show', $p->id) }}"
                           class="text-violet-600 font-semibold hover:text-violet-800 text-xs">
                            View &rarr;
                        </a>
                    </td>

                </tr>

            @empty
                <tr>
                    <td colspan="6" class="px-6 py-16 text-center text-slate-400 italic text-sm">
                        No participants found.
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
                <span class="font-semibold text-slate-600">{{ $participants->firstItem() ?? 0 }}</span> to
                <span class="font-semibold text-slate-600">{{ $participants->lastItem() ?? 0 }}</span> of
                <span class="font-semibold text-slate-600">{{ $participants->total() }}</span> participants
            </p>
            <div class="order-1 sm:order-2">
                {{ $participants->appends(request()->query())->links() }}
            </div>
        </div>

    </div>
    {{-- END MAIN CARD --}}


</div>
</div>
</x-app-layout>
