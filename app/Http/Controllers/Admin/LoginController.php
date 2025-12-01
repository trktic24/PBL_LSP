<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('login.login_admin'); 
    }

    /**
     * Memproses data login yang dikirim dari form.
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Coba login
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
            // 3. Jika berhasil, amankan sesi
            $request->session()->regenerate();

            // 4. Arahkan ke dashboard
            return redirect()->intended(route('dashboard'));
        }

        // 5. Jika gagal
        // [PERBAIKAN PENTING] Tambahkan withInput agar username tidak hilang
        return back()
            ->with('error', 'Username atau Password salah.')
            ->withInput($request->only('username')); 
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // [PERBAIKAN] Gunakan nama rute yang sudah kita definisikan di web.php
        return redirect()->route('login_admin');
    }

    /**
     * Menampilkan halaman lupa password.
     */
    public function showForgotPassword()
    {
        return view('login.forgot_pass'); 
    }

    /**
     * Menangani pengiriman link reset password.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['username' => 'required|string']);

        return back()->with('success', 'Jika username terdaftar, instruksi reset password akan dikirim (Fitur ini simulasi).');
    }
}