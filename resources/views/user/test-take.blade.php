<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-12">
        <div class="max-w-3xl mx-auto px-4">

            <div class="mb-8">
                <h1 class="text-2xl font-bold text-slate-800">Taking Test: {{ $category }}</h1>
                <p class="text-slate-500 text-sm">Please answer all questions carefully.</p>
            </div>

            <form action="{{ route('test.submit', $category) }}" method="POST" class="space-y-6">
                @csrf

                @foreach($questions as $index => $question)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
                        <p class="font-semibold text-slate-800 mb-4">
                            {{ $index + 1 }}. {{ $question->content }}
                        </p>

                        {{-- Options Group --}}
                        <div class="space-y-3">
                            @foreach(['A', 'B', 'C', 'D'] as $option)
                                <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 cursor-pointer hover:bg-slate-100 transition">
                                    <input type="radio" name="answer_{{ $question->id }}" value="{{ $option }}"
                                           class="text-[#1c1f23] focus:ring-[#1c1f23]">
                                    <span class="text-slate-700 text-sm">{{ $question->{'option_' . strtolower($option)} }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <button type="submit"
                        class="w-full bg-[#1c1f23] hover:bg-[#2e3238] text-white font-bold py-3 rounded-xl transition shadow-lg shadow-slate-200/50">
                    Submit My Answers
                </button>
            </form>

        </div>
    </div>
</x-app-layout>
