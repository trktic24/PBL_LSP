<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- PENTING

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     * (File: resources/views/login_admin.blade.php)
     */
    public function showLoginForm()
    {
        return view('login_admin'); 
    }

    /**
     * Memproses data login yang dikirim dari form.
     */
    public function login(Request $request)
    {
        // 1. Validasi input (wajib diisi)
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // 2. Coba login pakai 'username' dan 'password'
        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
            // 3. Jika berhasil, amankan sesi
            $request->session()->regenerate();

            // 4. Arahkan ke /dashboard
            return redirect()->intended('/dashboard');
        }

        // 5. Jika gagal, kembali ke halaman login dengan pesan error
        return back()->with('error', 'Username atau Password salah.');
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Arahkan kembali ke halaman login (rute '/')
        return redirect('/');
    }
}