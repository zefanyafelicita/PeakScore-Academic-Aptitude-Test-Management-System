<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
// menampilkan halaman verifikasi email jika user belum verifikasi emailnya, dan ketika sudah verifikasi maka akan diarahkan ke dashboard
class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     */
    public function __invoke(Request $request): RedirectResponse|View
    {
        // digunakan untuk mengecek apakah user sudah verifikasi emailnya atau belum, jika sudah maka akan diarahkan ke dashboard, jika belum maka akan menampilkan halaman verifikasi email
        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route('dashboard', absolute: false))
                    : view('auth.verify-email');
    }
}
