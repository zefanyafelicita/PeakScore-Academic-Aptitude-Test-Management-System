<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ParticipantController extends Controller
{
    // Only allow sorting on these specific columns
    private const SORTABLE = ['name', 'email', 'created_at', 'test_results_count'];

    // --- LISTING ALL PARTICIPANTS (WITH SEARCH + SORT) ---
    public function index(Request $request)
    {
        // Sort column (fallback to created_at)
        $sortBy  = in_array($request->input('sort'), self::SORTABLE)
            ? $request->input('sort')
            : 'created_at';

        // Sort direction (asc/desc)
        $sortDir = $request->input('direction') === 'asc' ? 'asc' : 'desc';

        // Fetch regular users + apply search filters + paginate
        $participants = User::query()
            ->where('is_admin', false)
            ->withCount('testResults')
            ->when($request->filled('search'), function ($query) use ($request) {
                $term = '%' . $request->input('search') . '%';
                $query->where(fn ($q) => $q
                    ->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                );
            })
            ->orderBy($sortBy, $sortDir)
            ->paginate(10)
            ->withQueryString();

        // Render index view
        return view('admin.participants.index', compact('participants', 'sortBy', 'sortDir'));
    }

    // --- SHOW SINGLE PARTICIPANT + TEST HISTORY ---
    public function show($id)
    {
        // Fetch user + attach latest tests (404 if not found)
        $participant = User::where('is_admin', false)
            ->with(['testResults' => fn ($q) => $q->latest()])
            ->withCount('testResults')
            ->findOrFail($id);

        // Render detail view
        return view('admin.participants.show', compact('participant'));
    }

   // --- EXPORT ALL PARTICIPANTS TO CSV ---
    public function export()
    {
        // Get all data sorted by name
        $participants = User::query()
            ->where('is_admin', false)
            ->withCount('testResults')
            ->orderBy('name')
            ->get();

        // Set unique filename with today's date
        $filename = 'participants_' . now()->format('Y-m-d') . '.csv';

        // Stream memory-safe download
        return response()->streamDownload(function () use ($participants) {
            $handle = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($handle, ['ID', 'Name', 'Email', 'Total Tests', 'Joined']);

            // Loop and inject data rows
            foreach ($participants as $p) {
                fputcsv($handle, [
                    $p->id,
                    $p->name,
                    $p->email,
                    $p->test_results_count,
                    $p->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
