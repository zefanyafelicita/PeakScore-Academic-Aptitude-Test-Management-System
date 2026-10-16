<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// ngirim ulang email verifikasi jika user belum verifikasi emailnya, dan ketika sudah verifikasi maka akan diarahkan ke dashboard
class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {
        // ngecek apakah user sudah verifikasi emailnya atau belum, jika sudah maka akan diarahkan ke dashboard, jika belum maka akan mengirim ulang email verifikasi
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        }
        // mengitim ulang email verifikasi, jika user belum verifikasi emailnya
        $request->user()->sendEmailVerificationNotification();
        // kembali ke halaman sebelumnya dengan status 'verification-link-sent' yang menandakan bahwa email verifikasi sudah dikirim ulang
        return back()->with('status', 'verification-link-sent');
    }
}
