<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

//Dipakai saat sudah login dan ingin mengganti password dari halaman profil/setting akun
class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    // kalau user teken change password
    public function update(Request $request): RedirectResponse
    {
        // buat ngecek password lama yang di input user, apakah sesuai dengan password yang ada di database atau tidak, 
        // jika tidak sesuai maka akan kembali ke halaman sebelumnya dengan error message, jika sesuai maka akan lanjut 
        // ke proses update password
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);
        // update password di database, dengan menghash password baru yang di input user
        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
        //password berhasil dibuat, kembalii ke halaman sbelumnya, dan menampilkan notifikasi bahwa password berhasil di update
        return back()->with('status', 'password-updated');
    }
}
