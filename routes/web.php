<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\Admin\AsesorController; 
use App\Http\Controllers\Admin\AsesiController; 
use App\Http\Controllers\Admin\TukController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Admin\DaftarHadirController;
use App\Http\Controllers\Admin\DetailSkemaController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AsesiProfileController;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Tuk;
use App\Models\Schedule;
use App\Models\Asesi;

/*
|--------------------------------------------------------------------------
| Rute Halaman Login & Logout
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login_admin');
Route::post('/login', [LoginController::class, 'login'])->name('login_admin.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// [PERBAIKAN] Rute Forgot Password (GET & POST)
// Menggunakan LoginController agar form bisa disubmit
Route::get('/forgot-password', [LoginController::class, 'showForgotPassword'])->name('forgot_pass');
Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('forgot_pass.send');


/*
|--------------------------------------------------------------------------
| Rute Panel Admin (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard', 'index')->name('dashboard');
    });

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
    Route::controller(DetailSkemaController::class)
         ->prefix('master/skema/detail')
         ->group(function () {
            Route::get('/{id_skema}', 'index')->name('skema.detail');

            Route::get('/{id_skema}/add-kelompok', 'createKelompok')->name('skema.detail.add_kelompok');
            Route::post('/{id_skema}/add-kelompok', 'storeKelompok')->name('skema.detail.store_kelompok');
            Route::get('/kelompok/{id_kelompok}/edit', 'editKelompok')->name('skema.detail.edit_kelompok');
            Route::put('/kelompok/{id_kelompok}', 'updateKelompok')->name('skema.detail.update_kelompok');
            Route::delete('/kelompok/{id_kelompok}', 'destroyKelompok')->name('skema.detail.destroy_kelompok');
            Route::delete('/unit/{id_unit}', 'destroyUnit')->name('skema.detail.destroy_unit');
            Route::put('/{id_skema}/update-form', 'updateListForm')->name('skema.detail.update_form');
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
        Route::delete('/attendance/destroy/{id}', [DaftarHadirController::class, 'destroy'])->name('schedule.attendance.destroy');
    });
    Route::get('/schedule_admin', [ScheduleController::class, 'showCalendar'])->name('schedule_admin');

    // 8. TUK
    Route::controller(TukController::class)->prefix('master/tuk')->group(function () {
        Route::get('/', 'index')->name('master_tuk');
        Route::get('/add', 'create')->name('add_tuk');
        Route::post('/add', 'store')->name('add_tuk.store');
        Route::get('/edit/{id}', 'edit')->name('edit_tuk');
        Route::patch('/update/{id}', 'update')->name('update_tuk');
        Route::delete('/delete/{id}', 'destroy')->name('delete_tuk');
    });

    // 9. Category
    Route::controller(CategoryController::class)->prefix('master/category')->group(function () {
        Route::get('/', 'index')->name('master_category');
        Route::get('/add', 'create')->name('add_category');
        Route::post('/add', 'store')->name('add_category.store');
        Route::get('/edit/{category}', 'edit')->name('edit_category');
        Route::patch('/update/{category}', 'update')->name('update_category');
        Route::delete('/delete/{category}', 'destroy')->name('delete_category');
    });

    // 10. Berita Terbaru
    Route::controller(BeritaController::class)->prefix('master/berita')->group(function () {
        Route::get('/', 'index')->name('master_berita');
        Route::get('/add', 'create')->name('add_berita');
        Route::post('/add', 'store')->name('add_berita.store');
        Route::get('/edit/{id}', 'edit')->name('edit_berita');
        Route::patch('/update/{id}', 'update')->name('update_berita');
        Route::delete('/delete/{id}', 'destroy')->name('delete_berita');
    });

    // 11. Profile Asesi
    Route::controller(AsesiProfileController::class)->prefix('asesi/{id_asesi}')->group(function () {
        Route::get('/settings', 'settings')->name('asesi.profile.settings');
        Route::get('/form', 'form')->name('asesi.profile.form');
        Route::get('/bukti', 'bukti')->name('asesi.profile.bukti');
        Route::get('/tracker', 'tracker')->name('asesi.profile.tracker');
    });

    // 12. Profile Asesor
    Route::get('/asesor/{id_asesor}/bukti', [AsesorController::class, 'showBukti'])->name('asesor.bukti');
    Route::get('/asesor/{id_asesor}/profile', [AsesorController::class, 'showProfile'])->name('asesor.profile');
    Route::get('/asesor_profile_tinjauan', function () { return view('profile_asesor.asesor_profile_tinjauan'); })->name('asesor_profile_tinjauan');
    Route::get('/asesor_profile_tracker', function () { return view('profile_asesor.asesor_profile_tracker'); })->name('asesor_profile_tracker');
});

require __DIR__.'/auth.php';