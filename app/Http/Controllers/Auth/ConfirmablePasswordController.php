<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

// memastikan ulang password user sebelum melakukan aksi yang sensitif, seperti mengubah email atau password, untuk meningkatkan keamanan akun user
class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password view.
     */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     */
    public function store(Request $request): RedirectResponse
    {
        // ngecek apakah password yang diinput user sesuai dengan password yang ada di database untuk user yang sedang login
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            // kalau password yang diinput tidak sesuai, maka akan dilempar error validasi dengan pesan yang sesuai
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }
        // kalau password yang diinput sudah benar, maka waktu konfirmasi password akan disimpan di session untuk digunakan nanti dalam menentukan apakah user perlu mengkonfirmasi password lagi atau tidak
        $request->session()->put('auth.password_confirmed_at', time());
        // jika password benar, maka akan diarahkan ke halaman dashboard atau halaman yang sebelumnya diakses sebelum diminta konfirmasi password
        return redirect()->intended(route('dashboard', absolute: false));
    }
}
