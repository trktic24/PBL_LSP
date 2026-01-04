<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // [PENTING] Untuk hapus/simpan file
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;
use App\Models\Admin; // [PENTING] Import Model Admin

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil user.
     */
    public function edit(Request $request): View
    {
        // Pastikan view mengarah ke file yang benar
        return view('Admin.profile.profile_admin', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update informasi profil user (Gabungan User & Admin).
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        // 1. Validasi Input
        $request->validate([
            'name' => ['required', 'string', 'max:255'], // Masuk ke tabel admin
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id_user . ',id_user'], // Validasi Unique Email (abaikan punya sendiri)
            'tanda_tangan_admin' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'], // Validasi Gambar (Max 2MB)
        ]);

        // 2. Update Data di Tabel USERS (Hanya Email)
        // Kita tidak update 'name' di table users karena 'name' sekarang ada di table 'admin'
        if ($user->email !== $request->email) {
            $user->email = $request->email;
            $user->email_verified_at = null; // Reset verifikasi jika email berubah
            $user->save();
        }

        // 3. Update Data di Tabel ADMIN
        // Cari data admin berdasarkan id_user, atau buat baru jika belum ada
        $adminData = Admin::firstOrNew(['id_user' => $user->id_user]);
        
        // Update Nama Admin
        $adminData->nama_admin = $request->name;

        // 4. Logika Upload Tanda Tangan
        if ($request->hasFile('tanda_tangan_admin')) {
            
            // Hapus file lama jika ada di storage
            if ($adminData->tanda_tangan_admin && Storage::disk('public')->exists($adminData->tanda_tangan_admin)) {
                Storage::disk('public')->delete($adminData->tanda_tangan_admin);
            }

            // Simpan file baru ke folder 'tanda_tangan' di disk 'public'
            $path = $request->file('tanda_tangan_admin')->store('tanda_tangan_admin', 'public');
            
            // Simpan path ke database
            $adminData->tanda_tangan_admin = $path;
        }

        $adminData->save();

        // Redirect kembali dengan pesan sukses
        return Redirect::route('admin.profile_admin')->with('status', 'profile-updated');
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

        // Hapus file tanda tangan dulu sebelum hapus user (Opsional, biar bersih)
        if ($user->admin && $user->admin->tanda_tangan_admin) {
            Storage::disk('public')->delete($user->admin->tanda_tangan_admin);
        }

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    /**
     * Update password user.
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

        return back()->with('status', 'password-updated');
    }
}