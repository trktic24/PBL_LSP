<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Exception;

class GoogleApiController extends Controller
{
    // step 1: redirect user ke Google
   public function redirect()
    {
        try {
            $redirectUrl = config('services.google.redirect_api');

            $authUrl = Socialite::driver('google')
                ->stateless()
                ->with(['redirect_uri' => $redirectUrl])
                ->redirect()
                ->getTargetUrl(); // ambil URL login Google

            return response()->json([
                'success' => true,
                'url' => $authUrl,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat URL Google redirect',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // step 2: callback dari Google
    public function callback(Request $request)
    {
        try {
            $redirectUrl = config('services.google.redirect_api');

            // pastikan redirect_uri di-pass ulang
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->with(['redirect_uri' => $redirectUrl])
                ->user();

            // cari user berdasarkan google_id atau email
            $user = User::where('google_id', $googleUser->getId())
                ->orWhere('email', $googleUser->getEmail())
                ->first();

            $isNew = false;

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->getName() ?? 'User ' . Str::random(5),
                    'email' => $googleUser->getEmail(),
                    'google_id' => $googleUser->getId(),
                    'password' => Hash::make(Str::random(12)),
                    'role_id' => 2, // default asesi (misalnya)
                ]);

                $isNew = true;
            } else {
                // sync google_id kalau sebelumnya login manual
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleUser->getId()]);
                }
            }

            // buat token sanctum
            $token = $user->createToken('api_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => $isNew ? 'User baru dibuat, lengkapi data.' : 'Login berhasil.',
                'is_new_user' => $isNew,
                'user' => $user,
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Google authentication failed.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
