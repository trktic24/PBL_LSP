<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil user.
     */
    public function edit(Request $request): View
    {
        // [PERBAIKAN] Arahkan ke view profile_admin yang sudah kita buat
        return view('profile.profile_admin', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil user.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // $request->validated() berisi data yang sudah lolos validasi (username & email)
        // Fungsi fill() akan otomatis mengisi kolom 'username' dan 'email' ke model User
        $request->user()->fill($request->validated());

        // Reset verifikasi email jika email berubah
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // [PERBAIKAN] Redirect ke route 'profile_admin' agar konsisten
        return Redirect::route('profile_admin')->with('status', 'profile-updated');
    }

    /**
     * Hapus akun user.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * [BARU] Update password user.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()],
        ], [
            'current_password.current_password' => 'Password saat ini salah.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Redirect kembali dengan status sukses khusus password
        return back()->with('status', 'password-updated');
    }
}