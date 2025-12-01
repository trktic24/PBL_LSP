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
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
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

    // // 1. HANYA Superadmin
    // Route::middleware(['role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    //     // Ganti 'viewLogs' dengan controller dashboard Superadmin
    //     Route::get('/logs', [SuperAdminController::class, 'viewLogs'])->name('logs');
    //     // ...rute superadmin lainnya
    // });
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
         // 1. Dashboard
        Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');    });
        // 2. Notification
    Route::get('/notifications', function () {
        return view('notifications.notifications_admin');
    })->name('notifications');

    // 3. Profile Admin
    Route::get('/profile_admin', function () {
        return view('profile.profile_admin');
    })->name('profile_admin');
    
    // Rute profil bawaan Laravel
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
        Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
    });
    // ==========================================================
    // 4. Master - Skema
    // ==========================================================
    Route::controller(SkemaController::class)->prefix('master/skema')->group(function () {
        Route::get('/', 'index')->name('master_skema');
        Route::get('/add', 'create')->name('add_skema');
        Route::post('/add', 'store')->name('add_skema.store');
        Route::get('/edit/{id_skema}', 'edit')->name('edit_skema');
        Route::patch('/update/{id_skema}', 'update')->name('update_skema');
        Route::delete('/delete/{id_skema}', 'destroy')->name('delete_skema');
    });
    Route::controller(\App\Http\Controllers\DetailSkemaController::class)
         ->prefix('master/skema/detail')
         ->group(function () {
            Route::get('/{id_skema}', 'index')->name('skema.detail');

            Route::get('/{id_skema}/add-kelompok', 'createKelompok')->name('skema.detail.add_kelompok');
            Route::post('/{id_skema}/add-kelompok', 'storeKelompok')->name('skema.detail.store_kelompok');
            Route::get('/kelompok/{id_kelompok}/edit', 'editKelompok')->name('skema.detail.edit_kelompok');
            Route::put('/kelompok/{id_kelompok}', 'updateKelompok')->name('skema.detail.update_kelompok');
            Route::delete('/kelompok/{id_kelompok}', 'destroyKelompok')->name('skema.detail.destroy_kelompok');
            Route::delete('/unit/{id_unit}', 'destroyUnit')->name('skema.detail.destroy_unit');
    });


    // 5. Master - Asesor
    Route::get('/master_asesor', [AsesorController::class, 'index'])->name('master_asesor');
    
    Route::get('/add_asesor1', [AsesorController::class, 'createStep1'])->name('add_asesor1');
    Route::post('/add_asesor1', [AsesorController::class, 'postStep1'])->name('add_asesor1.post');
    Route::get('/add_asesor2', [AsesorController::class, 'createStep2'])->name('add_asesor2');
    Route::post('/add_asesor2', [AsesorController::class, 'postStep2'])->name('add_asesor2.post');
    Route::get('/add_asesor3', [AsesorController::class, 'createStep3'])->name('add_asesor3');
    
    Route::post('/asesor/store', [AsesorController::class, 'store'])->name('asesor.store');
    
    Route::get('/edit-asesor-step-1/{id_asesor}', [AsesorController::class, 'editStep1'])->name('edit_asesor1');
    Route::patch('/update-asesor-step-1/{id_asesor}', [AsesorController::class, 'updateStep1'])->name('asesor.update.step1');
    Route::get('/edit-asesor-step-2/{id_asesor}', [AsesorController::class, 'editStep2'])->name('edit_asesor2');
    Route::patch('/update-asesor-step-2/{id_asesor}', [AsesorController::class, 'updateStep2'])->name('asesor.update.step2');
    Route::get('/edit-asesor-step-3/{id_asesor}', [AsesorController::class, 'editStep3'])->name('edit_asesor3');
    Route::patch('/update-asesor-step-3/{id_asesor}', [AsesorController::class, 'updateStep3'])->name('asesor.update.step3');

    Route::delete('/asesor/{id_asesor}', [AsesorController::class, 'destroy'])->name('asesor.destroy');
    
    // 6. Master - Asesi
    Route::controller(AsesiController::class)->prefix('master/asesi')->group(function () {
        Route::get('/', 'index')->name('master_asesi');
        Route::get('/add', 'create')->name('add_asesi');
        Route::post('/add', 'store')->name('add_asesi.store');
        Route::get('/edit/{id_asesi}', 'edit')->name('edit_asesi');
        Route::patch('/update/{id_asesi}', 'update')->name('update_asesi');
        Route::delete('/delete/{id_asesi}', 'destroy')->name('delete_asesi');
    });

    // 7. Master - Schedule
    Route::controller(ScheduleController::class)->prefix('master/schedule')->group(function () {
        Route::get('/', 'index')->name('master_schedule');
        Route::get('/add', 'create')->name('add_schedule');
        Route::post('/add', 'store')->name('add_schedule.store');
        Route::get('/edit/{id_jadwal}', 'edit')->name('edit_schedule');
        Route::patch('/update/{id_jadwal}', 'update')->name('update_schedule');
        Route::delete('/delete/{id_jadwal}', 'destroy')->name('delete_schedule');
        Route::get('/attendance/{id_jadwal}', [DaftarHadirController::class, 'index'])->name('schedule.attendance');
    });
    Route::get('/schedule_admin', [ScheduleController::class, 'showCalendar'])->name('schedule_admin');

    // 8. TUK
    Route::controller(TukAdminController::class)->prefix('master/tuk')->group(function () {
        Route::get('/', 'index')->name('master_tuk');
        Route::get('/add', 'create')->name('add_tuk');
        Route::post('/add', 'store')->name('add_tuk.store');
        Route::get('/edit/{id}', 'edit')->name('edit_tuk');
        Route::patch('/update/{id}', 'update')->name('update_tuk');
        Route::delete('/delete/{id}', 'destroy')->name('delete_tuk');
    });

    // 9. Master - Category (RUTE BARU - Konsisten pakai 'category')
    Route::controller(CategoryController::class)->prefix('master/category')->group(function () {
        Route::get('/', 'index')->name('master_category');
        Route::get('/add', 'create')->name('add_category');
        Route::post('/add', 'store')->name('add_category.store');
        Route::get('/edit/{category}', 'edit')->name('edit_category');
        Route::patch('/update/{category}', 'update')->name('update_category');
        Route::delete('/delete/{category}', 'destroy')->name('delete_category');
    });

    // 10. Profile Asesi
    Route::controller(AsesiProfileController::class)->prefix('asesi/{id_asesi}')->group(function () {
        Route::get('/settings', 'settings')->name('asesi.profile.settings');
        Route::get('/form', 'form')->name('asesi.profile.form');
        Route::get('/bukti', 'bukti')->name('asesi.profile.bukti');
        Route::get('/tracker', 'tracker')->name('asesi.profile.tracker');
    });

    // 11. Profile Asesor
    Route::get('/asesor/{id_asesor}/bukti', [AsesorController::class, 'showBukti'])->name('asesor.bukti');
    Route::get('/asesor/{id_asesor}/profile', [AsesorController::class, 'showProfile'])->name('asesor.profile');
    Route::get('/asesor_profile_tinjauan', function () { return view('profile_asesor.asesor_profile_tinjauan'); })->name('asesor_profile_tinjauan');
    Route::get('/asesor_profile_tracker', function () { return view('profile_asesor.asesor_profile_tracker'); })->name('asesor_profile_tracker');
    });
    // // 2. HANYA Admin dan Superadmin
    // Route::middleware(['role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    //     Route::get('/manage-users', [AdminController::class, 'manageUsers'])->name('users');

    //     // PENTING: Rute buat verifikasi Asesor
    //     Route::get('/verifikasi-asesor', [AdminVerifController::class, 'index'])->name('verif.index');
    //     Route::post('/verifikasi-asesor/{asesor}', [AdminVerifController::class, 'verify'])->name('verif.verify');

    //     // ...rute admin lainnya
    // });

    // // 3. HANYA Asesor (dan Admin/Superadmin jika perlu)
    // Route::middleware(['role:asesor,admin,superadmin'])->prefix('asesor')->name('asesor.')->group(function () {
    //     Route::get('/data-asesi', [AsesorController::class, 'viewAsesi'])->name('data.asesi');
    //     // ...rute asesor lainnya
    // });

    // // 4. HANYA Asesi
    // Route::middleware(['role:asesi'])->prefix('asesi')->name('asesi.')->group(function () {
    //     Route::get('/profile-saya', [AsesiController::class, 'myProfile'])->name('profile.saya');
    //     // ...rute asesi lainnya
    // });


    // --- RUTE DASHBOARD (HARUS PALING BAWAH) ---
    // Ini "Polisi Lalu Lintas" yang ngarahin user ke dashboard-nya masing2
    // setelah mereka login.
    Route::get('/dashboard', function (Request $request) {
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