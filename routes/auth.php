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

Route::middleware('guest')->group(function () {
    // Register routes
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

    // Login routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    // Password reset routes
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// ==========================================================
// 🟦 RUTE GOOGLE (Di luar grup)
// ==========================================================
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');


// ==========================================================
// 🟩 GRUP UNTUK USER (SUDAH LOGIN)
// ==========================================================
Route::middleware('auth')->group(function () {

    // 👇 PINDAHIN RUTE DASHBOARD KE SINI
    Route::get('/dashboard', function (Request $request) {

        $user = Auth::user();
        $roleName = $user->role->nama_role ?? null;

        if ($roleName === 'asesi') {
            return app(AsesiDashboardController::class)->index($request);
        } elseif ($roleName === 'asesor') {
            if (!$user->asesor?->is_verified) {
                Auth::logout();
                return redirect('/login')->with('error', 'Akun Anda belum diverifikasi.');
            }
            return app(AsesorDashboardController::class)->index($request);
        } elseif ($roleName === 'admin') {
            return app(AdminDashboardController::class)->index($request);
        }

        Auth::logout();
        return redirect('/login')->with('error', 'Role Anda tidak terdefinisi.');

    })->name('dashboard'); // <-- Rute 'dashboard' sekarang di sini

    // 👇 PINDAHIN RUTE PROFIL KE SINI
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Sisanya (confirm password, logout, dll)
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
