<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Asesi\DashboardController as AsesiDashboardController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;

// ======================================================
// --- RUTE GUEST (YANG BELUM LOGIN) ---
// ======================================================
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// --- RUTE GOOGLE AUTH ---
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


// ======================================================
// --- RUTE YANG WAJIB LOGIN ('auth') ---
// ======================================================
Route::middleware('auth')->group(function () {

    // Rute Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Rute Profil (Bisa diakses semua role)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rute Auth Bawaan Lainnya
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');


    /*
    |--------------------------------------------------------------------------
    | PENJELASAN UNTUK KELOMPOK LAIN (ADMIN, ASESOR, ASESI)
    |--------------------------------------------------------------------------
    */

    // --- RUTE DASHBOARD / HOME INTERAL ---
    // Ini "Polisi Lalu Lintas" yang mengarahkan user setelah login.
    //
    Route::get('/home', function (Request $request) {
        $user = Auth::user();
        $roleName = $user->role->nama_role ?? null;

        // 1. JIKA ASESI
        if ($roleName === 'asesi') {
            return app(AsesiDashboardController::class)->index($request);
        }

        // 2. JIKA ASESOR
        elseif ($roleName === 'asesor') {

            // --- CEK STATUS VERIFIKASI ---
            // Ambil kolom 'is_verified' (0 = Belum, 1 = Sudah)
            $isVerified = $user->asesor?->is_verified;

            // Jika data asesor belum ada ATAU belum diverifikasi (0)
            if (!$user->asesor || $isVerified == 0) {

                // TENDANG KELUAR (LOGOUT PAKSA)
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect()->route('login')
                    ->with('error', 'Akun Anda belum diverifikasi oleh Admin. Silakan tunggu persetujuan.');
            }

            // Jika lolos (is_verified == 1), masuk ke Dashboard Asesor
            return app(AsesorDashboardController::class)->index($request);
        }

        // 3. JIKA ADMIN
        elseif ($roleName === 'admin' || $roleName === 'superadmin') {
            return app(AdminDashboardController::class)->index($request);
        }

        // 4. JIKA ROLE TIDAK DIKENALI
        Auth::logout();
        return redirect('/login')->with('error', 'Role Anda tidak terdefinisi.');

    })->name('home.index');

});

// Halaman tunggu verifikasi (Opsional, jika Anda ingin redirect ke sini alih-alih logout)
// Saya sesuaikan juga kolomnya jadi 'is_verified' untuk jaga-jaga.
Route::get('/tunggu-verifikasi', function () {
    $user = Auth::user();

    if (!$user) return redirect()->route('login');
    if ($user->role->nama_role !== 'asesor') return redirect()->route('home.index');

    // Cek is_verified
    if ($user->asesor?->is_verified == 1) {
        return redirect()->route('home.index');
    }

    return view('auth.verification-asesor');
})->name('auth.wait');