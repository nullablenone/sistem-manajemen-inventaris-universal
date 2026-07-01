<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek apakah user mencentang fitur "Remember Me"
        $remember = $request->boolean('remember');

        // Auth::attempt otomatis mencocokkan hash password di database
        if (Auth::attempt($credentials, $remember)) {

            // Ini mencegah serangan 'Session Fixation' dengan membuat ID sesi baru 
            // setelah status user berubah dari 'guest' menjadi 'authenticated'.
            $request->session()->regenerate();

            // 'intended' akan melempar user ke URL yang mereka tuju sebelum terhadang halaman login,
            // atau jika tidak ada, default melempar ke '/dashboard'.
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'error' => 'Email atau kata sandi tidak cocok dengan database.',
        ]);
    }

    /**
     * Memproses logout user dan menghancurkan sesi.
     */
    public function logout(Request $request): RedirectResponse
    {
        // Keluarkan user dari status autentikasi
        Auth::logout();

        // Hancurkan seluruh data sesi saat ini agar tidak bisa dipakai ulang
        $request->session()->invalidate();

        // Buat ulang token CSRF untuk mencegah serangan pemalsuan request
        $request->session()->regenerateToken();

        // Arahkan kembali ke halaman login
        return redirect('/login');
    }
}
