<?php

use App\Http\Controllers\{
    ProfileController,
    QuestionController,
    TestResultController,
    TestController,
    ParticipantController
};
use App\Models\{User, TestResult};
use Illuminate\Support\Facades\{Route, Auth};

// --- PUBLIC ROUTES (GUESTS WELCOME) ---
Route::get('/', fn () => view('welcome'));

// --- AUTHENTICATED ROUTES (LOGIN REQUIRED) :D ---
Route::middleware(['auth', 'verified'])->group(function () {

    // 1. Dashboard: The main hub after logging in
    Route::get('/dashboard', [QuestionController::class, 'dashboard'])->name('dashboard');
    
    // 2. Profile: User account management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    // 3. Testing System: Where users take the exams
    Route::controller(TestController::class)->group(function () {
        Route::get('/test/{category}', 'showTest')->name('test.take');
        Route::post('/test/{category}/submit', 'submitTest')->name('test.submit');
    });

    // 4. Results: Viewing personal scores
    Route::get('/my-results', function () {
        $myResults = TestResult::where('user_id', Auth::id())->latest()->get();
        return view('user.user-results', compact('myResults'));
    })->name('user.results');

    // --- ADMIN ONLY GATEWAY ZONE :) ---
    Route::prefix('admin')->group(function () {

        // Test Results & Analytics
        Route::get('/test-results', [TestResultController::class, 'index'])->name('test-results.index');
        Route::get('/test-results/{result}', [TestResultController::class, 'show'])->name('test-results.show');

        // Participants
        Route::controller(ParticipantController::class)->group(function () {
            Route::get('/participants', 'index')->name('participants.index');
            Route::get('/participants/export', 'export')->name('participants.export');
            Route::get('/participants/{user}', 'show')->name('participants.show');
        });

        // Question Bank: Full CRUD (Create, Read, Update, Delete)
        Route::resource('questions', QuestionController::class);
    });
});

require __DIR__.'/auth.php';
