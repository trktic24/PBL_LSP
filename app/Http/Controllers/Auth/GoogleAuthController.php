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
                // Khusus untuk asesor: handle status
                if ($user->role?->nama_role === 'asesor') {
                    $status = $user->asesor?->status_verifikasi;

                    // GANTI BLOK INI
                    if ($status === 'pending') {
                        return redirect()->route('login')->with('error', 'Akun Anda sedang menunggu verifikasi Admin.');
                    }

                    if ($status === 'rejected') {
                        return redirect()->route('login')->with('error', 'Pendaftaran Anda ditolak. Silakan hubungi Admin.');
                    }
                }

                // Khusus untuk asesi: cek apakah profil sudah ada
                if ($user->role?->nama_role === 'asesi') {
                    if (!$user->asesi) {
                        return redirect()->route('login')->with('error', 'Profil Anda belum lengkap. Silakan daftar terlebih dahulu untuk melengkapi data profil.');
                    }
                }

                Auth::login($user);
                return redirect()->intended(route('dashboard'));
            }
            // Ambil role dari sesi saat klik “Continue with Google”
            $role = session('role_register', 'asesi'); // default asesi
            $roleModel = Role::where('nama_role', $role)->first();

            // Simpen dulu data Google di session (buat prefill form)
            session()->forget('google_register_data');
            session()->put('google_register_data', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'role' => $role,
                'role_id' => $roleModel?->id_role,
            ]);


            // Arahkan user ke step 2 register sesuai role
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
