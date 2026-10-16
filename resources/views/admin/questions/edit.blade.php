<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- 1. PAGE HEADER: Title and breadcrumbs --}}
            <div class="mb-8">
                <div class="flex items-center gap-2 text-xs text-slate-400 mb-4">
                    <a href="{{ route('questions.index') }}" class="hover:text-amber-600 transition">Question Bank</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="text-slate-600 font-medium">Edit Question</span>
                </div>

                <p class="text-xs font-semibold uppercase tracking-widest text-amber-500 mb-1">Editing</p>
                <h1 class="text-2xl font-bold text-slate-800 tracking-tight">Edit Question</h1>
                <p class="mt-1 text-sm text-slate-500">Update the question details below. Changes are saved immediately upon submission.</p>
            </div>

            {{-- 2. ERROR HANDLING: Displays feedback if the update validation fails --}}
            @if ($errors->any())
                <div class="mb-6 flex gap-3 bg-rose-50 border border-rose-200 text-rose-700 px-5 py-4 rounded-xl shadow-sm">
                    <span class="flex-shrink-0 mt-0.5"><svg class="w-5 h-5 text-rose-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg></span>
                    <div>
                        <p class="text-sm font-semibold mb-1">Please fix the following errors:</p>
                        <ul class="list-disc list-inside text-sm space-y-0.5">
                            @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- 3. MAIN FORM: Loads existing data and sends PUT request to update --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
                <div class="h-1 w-full bg-gradient-to-r from-amber-400 via-amber-300 to-amber-500"></div>

                <form action="{{ route('questions.update', $question->id) }}" method="POST" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8 space-y-7">

                        {{-- Section: Core Meta --}}
                        <div>
                            <h2 class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-5 pb-3 border-b border-slate-100">Question Details</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

                                {{-- Category Input --}}
                                <div class="sm:col-span-1">
                                    <label for="category" class="block text-sm font-semibold text-slate-700 mb-1.5">Category *</label>
                                    <select id="category" name="category" class="w-full bg-slate-50 border {{ $errors->has('category') ? 'border-rose-400' : 'border-slate-200' }} text-slate-700 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-400 transition cursor-pointer">
                                        @foreach(['Verbal', 'Numerik', 'Logika', 'Spasial'] as $cat)
                                            <option value="{{ $cat }}" {{ old('category', $question->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                        @endforeach
                                    </select>
                                    @error('category') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                                </div>

                                {{-- Correct Answer Input --}}
                                <div class="sm:col-span-1">
                                    <label for="correct_answer" class="block text-sm font-semibold text-slate-700 mb-1.5">Correct Answer *</label>
                                    <select id="correct_answer" name="correct_answer" class="w-full bg-slate-50 border {{ $errors->has('correct_answer') ? 'border-rose-400' : 'border-slate-200' }} text-slate-700 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-400 transition cursor-pointer">
                                        @foreach(['A', 'B', 'C', 'D'] as $ans)
                                            <option value="{{ $ans }}" {{ old('correct_answer', $question->correct_answer) === $ans ? 'selected' : '' }}>Option {{ $ans }}</option>
                                        @endforeach
                                    </select>
                                    @error('correct_answer') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            {{-- Question Text --}}
                            <div class="mt-6">
                                <label for="content" class="block text-sm font-semibold text-slate-700 mb-1.5">Question Content *</label>
                                <textarea id="content" name="content" rows="4" class="w-full bg-slate-50 border {{ $errors->has('content') ? 'border-rose-400' : 'border-slate-200' }} text-slate-700 text-sm rounded-xl px-4 py-3 focus:ring-2 focus:ring-amber-400 transition resize-none">{{ old('content', $question->content) }}</textarea>
                                @error('content') <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Section: Answer Options --}}
                        <div>
                            <h2 class="text-xs font-semibold uppercase tracking-widest text-slate-400 mb-5 pb-3 border-b border-slate-100">Answer Options</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                @foreach(['a' => 'A', 'b' => 'B', 'c' => 'C', 'd' => 'D'] as $key => $label)
                                    <div>
                                        <label for="option_{{ $key }}" class="flex items-center gap-2 text-sm font-semibold text-slate-700 mb-1.5">
                                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-md bg-amber-100 text-amber-700 text-xs font-bold">{{ $label }}</span>
                                            Option {{ $label }} *
                                        </label>
                                        <input type="text" id="option_{{ $key }}" name="option_{{ $key }}" value="{{ old('option_' . $key, $question->{'option_' . $key}) }}"
                                            class="w-full bg-slate-50 border {{ $errors->has('option_'.$key) ? 'border-rose-400' : 'border-slate-200' }} text-slate-700 text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-amber-400 transition">
                                        @error('option_' . $key) <p class="mt-1.5 text-xs text-rose-500">{{ $message }}</p> @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- 4. FORM FOOTER: Update or Cancel buttons --}}
                    <div class="px-6 sm:px-8 py-5 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-4">
                        <a href="{{ route('questions.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition">Cancel</a>
                        <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md transition">Update Question</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
