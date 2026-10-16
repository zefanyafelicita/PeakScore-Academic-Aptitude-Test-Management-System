<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
// controller ini untuk menangani proses login dan logout user
class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // pas tombol login diteken, maka akan masuk ke method store() ini
    public function store(LoginRequest $request): RedirectResponse
    {
        // ngecek email dan password yang diinput user, apakah sesuai dengan data yang ada di database atau tidak
        $request->authenticate();
        // kalau sudah berhasil login, maka session akan diregenerate/dibuat baru untuk mencegah session fixation
        $request->session()->regenerate();
        // setelah login berhasil, maka akan diarahkan ke halaman dashboard
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    // pas tombol logout diteken, maka akan masuk ke method destroy() ini
    public function destroy(Request $request): RedirectResponse
    {
        // nge-logout user dari session, jadi data user yang login akan dihapus dari session
        Auth::guard('web')->logout();
        // menghapus semua data session yang tersisa setelah logout, untuk memastikan tidak ada data session yang tertinggal
        $request->session()->invalidate();
        // membuat token baru untuk session yang baru, untuk mencegah CSRF attack setelah logout
        $request->session()->regenerateToken();
        // kembali ke halaman utama setelah logout berhasil
        return redirect('/');
    }
}
