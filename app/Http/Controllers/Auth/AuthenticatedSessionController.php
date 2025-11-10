<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
// use App\Http\Requests\Auth\LoginRequest; // Ini kayaknya nggak kepake, bisa dihapus
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

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
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();
            if ($user->role?->nama_role === 'asesor') {

                    // Ambil status dari tabel asesor (pake null-safe '?' biar aman)
                    $status = $user->asesor?->status_verifikasi;

                    if ($status === 'pending') {
                        Auth::logout(); // Logout paksa
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')
                            ->with('error', 'Akun Anda sedang menunggu verifikasi Admin.');
                    }

                    if ($status === 'rejected') {
                        Auth::logout(); // Logout paksa
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        return redirect()->route('login')
                            ->with('error', 'Pendaftaran Anda ditolak. Silakan hubungi Admin.');
                    }

                }

            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);

    }

    /**
     * Destroy an authenticated session.
     * *
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
