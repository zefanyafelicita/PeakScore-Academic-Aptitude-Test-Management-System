<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// buat user take test, dan submit jawaban user, lalu menyimpan hasil test
class TestController extends Controller
{
    // Categories
    private const CATEGORIES = ['Verbal', 'Numerik', 'Logika', 'Spasial'];

    // Show the test page for a category
    public function showTest(string $category)
    {
        abort_if(!in_array($category, self::CATEGORIES), 404);

        $questions = Question::where('category', $category)->get();

        return view('user.test-take', compact('questions', 'category'));
    }

    // Grade the answers and save the score
    public function submitTest(Request $request, string $category)
    {
        abort_if(!in_array($category, self::CATEGORIES), 404);

        // Always re-fetch from DB so the user can't mess with which questions get graded
        $questions = Question::where('category', $category)->get();
        $total     = $questions->count();

        // Count correct answers
        $correctCount = $questions->filter(fn ($q) =>
            $request->input('answer_' . $q->id) === $q->correct_answer
        )->count();

        $score = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        TestResult::create([
            'user_id'      => Auth::id(),
            'category'     => $category,
            'score'        => $score,
            'question_ids' => $questions->pluck('id'),
        ]);

        return redirect()->route('dashboard')->with('success', 'Test submitted successfully!');
    }
}
