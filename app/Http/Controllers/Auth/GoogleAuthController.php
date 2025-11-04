<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    public function redirect(Request $request)
    {
        if ($request->has('role')) {
            session(['role_register' => $request->get('role')]);
        }

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // 🔍 Cek apakah user sudah ada di DB
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
            Auth::login($user);

            $roleName = $user->role->nama_role ?? null; // ambil nama role dari relasi

            if ($roleName === 'asesi') {
                return redirect()->route('asesi.dashboard');
            } elseif ($roleName === 'asesor') {
                return redirect()->route('asesor.dashboard');
            } elseif ($roleName === 'admin') {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('dashboard');
            }
        }


            // 🧠 Ambil role dari sesi saat klik “Continue with Google”
            $role = session('role_register', 'asesi'); // default asesi
            $roleModel = Role::where('nama_role', $role)->first();

            // 🧩 Simpen dulu data Google di session (buat prefill form)
            session()->forget('google_register_data');
            session()->put('google_register_data', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'role' => $role,
                'role_id' => $roleModel?->id_role,
            ]);


            // 🚀 Arahkan user ke step 2 register sesuai role
            return redirect()->route('register', [
                'step' => 2,
                'role' => $role,
            ]);

        } catch (\Exception $e) {
            Log::error('Google Auth Callback Error: ' . $e->getMessage());

            return redirect()->route('login')->withErrors([
                'email' => 'Gagal mengautentikasi dengan Google. Silakan coba lagi.'
            ]);
        }
    }
}
