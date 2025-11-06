<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\AsesorController; 
use App\Http\Controllers\AsesiController; 
use App\Models\Skema;  // <-- Pastikan ini ada
use App\Models\Asesor; // <-- Pastikan ini ada

/*
|--------------------------------------------------------------------------
| Rute Halaman Login & Logout
|--------------------------------------------------------------------------
*/
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Rute Panel Admin (Semua di sini WAJIB LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard.dashboard_admin');
    })->name('dashboard');

    // 2. Notification
    Route::get('/notifications', function () {
        return view('notifications.notifications_admin');
    })->name('notifications');

    // 3. Profile Admin
    Route::get('/profile_admin', function () {
        return view('profile.profile_admin');
    })->name('profile_admin');

    // 4. Master - Skema
    Route::get('/master_skema', function () {
        $skemas = Skema::all(); 
        return view('master.skema.master_skema', [
            'skemas' => $skemas // Mengirim data skema ke view
        ]);
    })->name('master_skema');
    Route::get('/add_skema', function () {
        return view('master.skema.add_skema');
    })->name('add_skema');
    Route::post('/add_skema', [SkemaController::class, 'store'])->name('add_skema.store');

    // 5. Master - Asesor
    Route::get('/master_asesor', function () {
        // Ambil semua asesor, dan muat relasi 'user' dan 'skema'
        $asesors = \App\Models\Asesor::with(['user', 'skema'])->get(); 
        
        return view('master.asesor.master_asesor', [
            'asesors' => $asesors
        ]);
    })->name('master_asesor');

    // Rute Form Step 1
    Route::get('/add_asesor1', [AsesorController::class, 'createStep1'])->name('add_asesor1');
    Route::post('/add_asesor1', [AsesorController::class, 'postStep1'])->name('add_asesor1.post');
    
    // Rute Form Step 2
    Route::get('/add_asesor2', [AsesorController::class, 'createStep2'])->name('add_asesor2');
    Route::post('/add_asesor2', [AsesorController::class, 'postStep2'])->name('add_asesor2.post');

    // Rute Form Step 3 (Final)
    Route::get('/add_asesor3', [AsesorController::class, 'createStep3'])->name('add_asesor3');
    Route::post('/store-asesor', [AsesorController::class, 'store'])->name('asesor.store');
    
    // Rute Edit
    Route::get('/edit-asesor-step-1/{id_asesor}', [AsesorController::class, 'editStep1'])
         ->name('edit_asesor1');
    Route::patch('/update-asesor-step-1/{id_asesor}', [AsesorController::class, 'updateStep1'])
         ->name('asesor.update.step1');

    // Halaman Edit Step 2 (Data Pribadi)
    Route::get('/edit-asesor-step-2/{id_asesor}', [AsesorController::class, 'editStep2'])
         ->name('edit_asesor2');
    Route::patch('/update-asesor-step-2/{id_asesor}', [AsesorController::class, 'updateStep2'])
         ->name('asesor.update.step2');

    // Halaman Edit Step 3 (Kelengkapan Dokumen)
    Route::get('/edit-asesor-step-3/{id_asesor}', [AsesorController::class, 'editStep3'])
         ->name('edit_asesor3');
    Route::patch('/update-asesor-step-3/{id_asesor}', [AsesorController::class, 'updateStep3'])
         ->name('asesor.update.step3');
    
    // 6. Master - Asesi
    // HANYA ADA SATU RUTE MASTER_ASESI: Mengarah ke Controller
    Route::get('/master_asesi', [AsesiController::class, 'index'])->name('master_asesi');
    
    // Rute-rute tambah Asesi (Step 1-4)
    Route::get('/add-asesi-step-1', function () {
        return view('master.asesi.add_asesi1');
    })->name('add_asesi1');
    Route::get('/add-asesi-step-2', function () {
        return view('master.asesi.add_asesi2');
    })->name('add_asesi2');
    Route::get('/add-asesi-step-3', function () {
        return view('master.asesi.add_asesi3');
    })->name('add_asesi3');
    Route::get('/add-asesi-step-4', function () {
        return view('master.asesi.add_asesi4');
    })->name('add_asesi4');
    Route::post('/store-asesi', function () {
        return redirect()->route('master_asesi')->with('success', 'Asesi berhasil ditambahkan!');
    })->name('asesi.store');
    

    // 7. Master - Schedule
    Route::get('/schedule_admin', function () {
        return view('master.schedule.schedule_admin');
    })->name('schedule_admin');
    Route::get('/master_schedule', function () {
        return view('master.schedule.master_schedule');
    })->name('master_schedule');
    Route::get('/add_schedule', function () {
        return view('master.schedule.add_schedule');
    })->name('add_schedule');
    Route::get('/edit_schedule', function () {
        return view('master.schedule.edit_schedule');
    })->name('edit_schedule');
    
    // 8. TUK
    Route::get('/tuk_all', function () {
    return view('tuk.tuk_all');
    })->name('tuk_all');
    Route::get('/tuk_sewaktu', function () {
        return view('tuk.tuk_sewaktu');
    })->name('tuk_sewaktu');
    Route::get('/add_tuk', function () {
        return view('tuk.add_tuk');
    })->name('add_tuk');
    Route::get('/tuk_tempatkerja', function () {
        return view('tuk.tuk_tempatkerja');
    })->name('tuk_tempatkerja');
    Route::get('/edit_tuk', function () {
        return view('tuk.edit_tuk');
    })->name('edit_tuk');

    // 9. Profile Asesi
    Route::get('/asesi_profile_settings', function () {
        return view('profile_asesi.asesi_profile_settings');
    })->name('asesi_profile_settings');
    Route::get('/asesi_profile_form', function () {
        return view('profile_asesi.asesi_profile_form');
    })->name('asesi_profile_form');
    Route::get('/asesi_profile_bukti', function () {
        return view('profile_asesi.asesi_profile_bukti');
    })->name('asesi_profile_bukti');
    Route::get('/asesi_profile_tracker', function () {
        return view('profile_asesi.asesi_profile_tracker');
    })->name('asesi_profile_tracker');

    // 10. Profile Asesor
    Route::get('/asesor_profile_bukti', function () {
        return view('profile_asesor.asesor_profile_bukti');
    })->name('asesor_profile_bukti');
    Route::get('/asesor_profile_settings', function () {
        return view('profile_asesor.asesor_profile_settings');
    })->name('asesor_profile_settings');
    Route::get('/asesor_profile_tinjauan', function () {
        return view('profile_asesor.asesor_profile_tinjauan');
    })->name('asesor_profile_tinjauan');
    Route::get('/asesor_profile_tracker', function () {
        return view('profile_asesor.asesor_profile_tracker');
    })->name('asesor_profile_tracker');

    // Rute profil bawaan Laravel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
