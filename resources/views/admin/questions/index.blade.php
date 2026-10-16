<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 1. PAGE HEADER: Title & "Add" button for admins --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-widest text-violet-500 mb-1">Question Bank</p>
                    <h1 class="text-2xl font-bold text-slate-800 tracking-tight">TPA Questions</h1>
                    <p class="mt-1 text-sm text-slate-500">Manage all Academic Potential Test questions.</p>
                </div>

                @if(auth()->user()->is_admin)
                    <a href="{{ route('questions.create') }}" class="inline-flex items-center gap-2 bg-[#1c1f23] hover:bg-[#2e3238] text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                        Add New Question
                    </a>
                @endif
            </div>

            {{-- 2. SUCCESS MESSAGE: Shows up briefly after you save/delete --}}
            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-3.5 rounded-xl shadow-sm">
                    <span class="text-sm font-medium">✓ {{ session('success') }}</span>
                </div>
            @endif

            {{-- 3. THE MAIN TABLE CARD --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">

                {{-- Toolbar: Search box and total count --}}
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/60 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
                    <form action="{{ route('questions.index') }}" method="GET" class="flex gap-2 w-full sm:max-w-md">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Search content or category…"
                               class="w-full pl-4 py-2 text-sm border border-slate-200 rounded-lg focus:ring-2 focus:ring-violet-400">
                    </form>
                    <p class="text-xs text-slate-400 font-medium">{{ $questions->total() }} questions found</p>
                </div>

                {{-- The Data Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">#</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Category</th>
                                <th class="px-6 py-3.5 text-left text-xs font-semibold text-slate-500 uppercase">Question Content</th>
                                @if(auth()->user()->is_admin)
                                    <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($questions as $index => $soal)
                                <tr class="hover:bg-violet-50/40 transition">
                                    <td class="px-6 py-4 text-slate-400 text-xs font-medium">{{ $questions->firstItem() + $index }}</td>

                                    {{-- Category Badge --}}
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold ring-1 ring-inset {{
                                            ['Verbal'=>'bg-blue-50 text-blue-700 ring-blue-600/20', 'Numerik'=>'bg-amber-50 text-amber-700 ring-amber-600/20',
                                             'Logika'=>'bg-violet-50 text-violet-700 ring-violet-600/20', 'Spasial'=>'bg-emerald-50 text-emerald-700 ring-emerald-600/20'][$soal->category] ?? 'bg-slate-50 text-slate-600'
                                        }}">
                                            {{ $soal->category }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-slate-600 max-w-xl"><span class="line-clamp-2">{{ $soal->content }}</span></td>

                                    @if(auth()->user()->is_admin)
                                        <td class="px-6 py-4 text-right">
                                            <div class="inline-flex gap-2">
                                                <a href="{{ route('questions.edit', $soal->id) }}" class="text-xs font-semibold text-[#1c1f23] bg-slate-100 hover:bg-[#1c1f23] hover:text-white px-3 py-1.5 rounded-lg transition duration-150">Edit</a>
                                                <form action="{{ route('questions.destroy', $soal->id) }}" method="POST" onsubmit="return confirm('Sure?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-medium text-rose-600 bg-rose-50 px-3 py-1.5 rounded-lg">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-20 text-center text-slate-400">No questions found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- 4. PAGINATION: Clickable buttons to see the next page of questions --}}
                @if($questions->hasPages())
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $questions->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
