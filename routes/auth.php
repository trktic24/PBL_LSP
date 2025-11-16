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
    // ... (Komentar grup-grup role Anda tetap di sini) ...
    // // 1. HANYA Superadmin
    // ...
    // // 2. HANYA Admin dan Superadmin
    // ...
    // // 3. HANYA Asesor
    // ...
    // // 4. HANYA Asesi
    // ...


    // --- RUTE DASHBOARD (HARUS PALING BAWAH) ---
    // Ini "Polisi Lalu Lintas" yang ngarahin user ke dashboard-nya masing2
    // setelah mereka login.
    //
    // *** INI ADALAH PERUBAHAN YANG ANDA MINTA ***
    // URL diubah dari '/dashboard' menjadi '/home'
    // Nama rute diubah dari 'dashboard' menjadi 'home.index'
    //
    /*Route::get('/home', function (Request $request) {
        $user = Auth::user();
        $roleName = $user->role->nama_role ?? null;

        if ($roleName === 'asesi') {
            return app(AsesiDashboardController::class)->index($request);// Arahin ke route Asesi
        }elseif ($roleName === 'asesor') {

            // Logika blokir asesor 'pending'/'rejected' tetep di sini
            $status = $user->asesor?->status_verifikasi;

            if ($status === 'pending') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Akun Anda sedang menunggu verifikasi Admin.');
            }
            if ($status === 'rejected') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')
                    ->with('error', 'Pendaftaran Anda ditolak. Silakan hubungi Admin.');
            }

            // ✅ UDAH DIGANTI PAKE CONTROLLER LU (kalo lolos/approved)
            return app(AsesorDashboardController::class)->index($request);

        } elseif ($roleName === 'admin' || $roleName === 'superadmin') {
            // ✅ UDAH DIGANTI PAKE CONTROLLER LU
            return app(AdminDashboardController::class)->index($request);
        }

        // Kalo role-nya aneh, tendang
        Auth::logout();
        return redirect('/login')->with('error', 'Role Anda tidak terdefinisi.');

    })->name('home.index'); */

});


Route::get('/tunggu-verifikasi', function () {
    $user = Auth::user();

    if (!$user) return redirect()->route('login');

    // FIX: Arahkan ke rute baru 'home.index' jika user bukan asesor
    if ($user->role->nama_role !== 'asesor') {
        return redirect()->route('home.index');
    }
    // FIX: Arahkan ke rute baru 'home.index' jika status bukan pending
    if ($user->asesor?->status_verifikasi !== 'pending') {
        return redirect()->route('home.index');
    }

    return view('auth.verification-asesor');
})->name('auth.wait');