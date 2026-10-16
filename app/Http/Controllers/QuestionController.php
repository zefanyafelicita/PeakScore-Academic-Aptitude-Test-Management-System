<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\User;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
//  CRUD soal untuk admin, dan dashboard untuk admin dan user, di dashboard admin bisa melihat statistik soal dan hasil test, sedangkan di dashboard user bisa melihat hasil test yang sudah diambil
class QuestionController extends Controller
{
    // --- LISTING ALL QUESTIONS ---
    public function index(Request $request)
    {
        $search = $request->input('search');
        $sort   = $request->input('sort', 'created_at');
        $direction = $request->input('direction', 'desc');

        // Build the query and add search/sort filters
        $questions = Question::query()
            ->when($search, function ($query, $search) {
                return $query->where('content', 'like', "%{$search}%")
                             ->orWhere('category', 'like', "%{$search}%");
            })
            ->orderBy($sort, $direction)
            ->paginate(5)
            ->withQueryString();

        return view('admin.questions.index', compact('questions', 'search'));
    }

    // --- ADMIN: SHOW CREATE FORM ---
    public function create()
    {
        // Only admins allowed! >:(
        if (!Auth::user()->is_admin) abort(403, 'Unauthorized action. Admin eyes only!');

        return view('admin.questions.create');
    }

    // --- ADMIN: SAVE NEW QUESTION ---
    public function store(Request $request)
    {
        if (!Auth::user()->is_admin) abort(403, 'Unauthorized action. Admin eyes only!');

        $validated = $request->validate([
            'category'       => 'required',
            'content'        => 'required',
            'option_a'       => 'required',
            'option_b'       => 'required',
            'option_c'       => 'required',
            'option_d'       => 'required',
            'correct_answer' => 'required|max:1',
        ]);

        Question::create($validated);
        return redirect()->route('questions.index')->with('success', 'Question added!');
    }

    // --- ADMIN: SHOW EDIT FORM ---
    public function edit(Question $question)
    {
        if (!Auth::user()->is_admin) abort(403, 'Unauthorized action. Admin eyes only!');

        return view('admin.questions.edit', compact('question'));
    }

    // --- ADMIN: SAVE EDITED QUESTION ---
    public function update(Request $request, Question $question)
    {
        if (!Auth::user()->is_admin) abort(403, 'Unauthorized action. Admin eyes only!');

        $validated = $request->validate([
            'category'       => 'required',
            'content'        => 'required',
            'option_a'       => 'required',
            'option_b'       => 'required',
            'option_c'       => 'required',
            'option_d'       => 'required',
            'correct_answer' => 'required|max:1',
        ]);

        $question->update($validated);
        return redirect()->route('questions.index')->with('success', 'Question updated!');
    }

    // --- ADMIN: DELETE QUESTION ---
    public function destroy(Question $question)
    {
        if (!Auth::user()->is_admin) abort(403, 'Unauthorized action. Admin eyes only!');

        $question->delete();
        return redirect()->route('questions.index')->with('success', 'Question deleted!');
    }

    // --- DASHBOARD: VIEW FOR ADMINS & USERS ---
    public function dashboard()
    {
        // If it's an admin, show them the big-picture stats
        if (Auth::user()->is_admin) {
            $stats = [
                'totalQuestions'    => Question::count(),
                'totalParticipants' => User::where('is_admin', false)->count(),
                'testsCompleted'    => TestResult::count(),
                'verbalCount'       => Question::where('category', 'Verbal')->count(),
                'numerikCount'      => Question::where('category', 'Numerik')->count(),
                'logikaCount'       => Question::where('category', 'Logika')->count(),
                'spasialCount'      => Question::where('category', 'Spasial')->count(),
            ];

            return view('user.dashboard', $stats);
        }

        // If it's a regular user, show them their own results
        $myResults = TestResult::where('user_id', Auth::id())->latest()->get();
        return view('user.user-results', compact('myResults'));
    }
}
