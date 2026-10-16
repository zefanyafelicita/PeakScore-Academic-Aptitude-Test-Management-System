<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// controller hasil test
class TestResultController extends Controller
{
    // --- ADMIN: SHOW ALL TEST RESULTS + METRICS ---
    public function index(Request $request)
    {
        // Admin gate check! :D
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action. This page is restricted to Admins only.');
        }

        $search    = $request->input('search');
        $sort      = in_array(request('sort'), ['user_id', 'created_at', 'category', 'score'])
                 ? request('sort')
                 : 'created_at';
        $direction = request('direction') === 'asc' ? 'asc' : 'desc';

        // Start a query and eagerly load the user to avoid "N+1" performance issues (load slower)
        $query = TestResult::with('user');

        // Filter results by participant name if search is active
        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $results = TestResult::with('user')
               ->when(request('search'),   fn($q, $s)   => $q->whereHas('user', fn($q) => $q->where('name', 'like', "%$s%")))
               ->when(request('category'), fn($q, $cat) => $q->where('category', $cat))
               ->orderBy($sort, $direction)
               ->paginate(10);

        // Calculate key metrics for the report
        $totalCompleted = TestResult::count();
        $totalPassed    = TestResult::where('score', '>=', 60)->count();

        $metrics = [
            'results'        => $results,
            'avgScore'       => TestResult::avg('score') ?? 0,
            'highestScore'   => TestResult::max('score') ?? 0,
            'lowestScore'    => TestResult::min('score') ?? 0,
            'passRate'       => $totalCompleted > 0 ? ($totalPassed / $totalCompleted) * 100 : 0,
            'search'         => $search,
            'totalQuestions' => Question::count(),
        ];

        return view('admin.test-results.index', $metrics);
    }

    // --- ADMIN: VIEW SPECIFIC TEST ATTEMPT ---
    public function show(int $id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403, 'Unauthorized action.');
        }

        // Get the result and load the related user
        $testResult = TestResult::with('user')->findOrFail($id);

        // Fetch all questions for the category the user was tested on
        $questions = Question::where('category', $testResult->category)->get();

        return view('admin.test-results.show', compact('testResult', 'questions'));
    }
}
