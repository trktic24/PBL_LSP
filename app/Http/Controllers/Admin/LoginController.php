<?php

namespace App\Http\Controllers\Admin; // Namespace adjusted if file is in Admin folder

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm(): View
    {
        return view('login.login_admin'); 
    }

    /**
     * Memproses data login yang dikirim dari form.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Memproses logout.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();
        
        return redirect()->route('login_admin');
    }

    /**
     * Menampilkan halaman lupa password.
     */
    public function showForgotPassword(): View
    {
        return view('login.forgot_pass'); 
    }

    /**
     * Menangani pengiriman link reset password.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate(['email' => 'required|email']);

        // Kirim link reset password menggunakan mekanisme bawaan Laravel/Breeze
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status == Password::RESET_LINK_SENT
                    ? back()->with('status', __($status))
                    : back()->withInput($request->only('email'))
                            ->withErrors(['email' => __($status)]);
    }
}