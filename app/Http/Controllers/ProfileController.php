<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // --- SHOW PROFILE EDIT PAGE ---
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(), // Send current user data to form
        ]);
    }

    // --- UPDATE PROFILE (NAME + EMAIL) ---
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Fill model with validated form inputs
        $request->user()->fill($request->validated());

        // Reset email verification if email was changed
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        // Save to database
        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // --- PERMANENTLY DELETE ACCOUNT ---
    public function destroy(Request $request): RedirectResponse
    {
        // 1. Password check for safety precaution!
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // 2. Log out user first (prevents errors)
        Auth::logout();

        // 3. Goodbye, data! :D
        $user->delete();

        // 4. Secure session & refresh tokens
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/'); // Back to homepage
    }
}
