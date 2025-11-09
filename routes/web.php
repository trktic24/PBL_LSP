<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\AsesorController; 
use App\Http\Controllers\AsesiController; 
use App\Http\Controllers\TukController;
use App\Http\Controllers\ScheduleController;
use App\Models\Skema;
use App\Models\Asesor;
use App\Models\Tuk;
use App\Models\Schedule;
use App\Models\Asesi;
// UnitKompetensi tidak kita panggil lagi di sini
// use App\Models\UnitKompetensi; 

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
| Rute Panel Admin (Wajib Login)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // 1. Dashboard
    Route::get('/dashboard', function () {
        // 3. TAMBAHKAN LOGIKA UNTUK MENGAMBIL DATA
        $asesmenBerlangsung = Schedule::where('Status_jadwal', 'Terjadwal')->count();
        $asesmenSelesai = Schedule::where('Status_jadwal', 'Selesai')->count();
        $jumlahAsesi = Asesi::count();
        $jumlahAsesor = Asesor::count();
        
        $jadwalTerbaru = Schedule::with('skema') // Ambil relasi skema
                            ->where('Status_jadwal', 'Terjadwal')
                            ->orderBy('tanggal_pelaksanaan', 'asc') // Tampilkan yang paling dekat
                            ->take(5) // Batasi hanya 5
                            ->get();

        // 4. KIRIM DATA KE VIEW
        return view('dashboard.dashboard_admin', [
            'asesmenBerlangsung' => $asesmenBerlangsung,
            'asesmenSelesai' => $asesmenSelesai,
            'jumlahAsesi' => $jumlahAsesi,
            'jumlahAsesor' => $jumlahAsesor,
            'jadwalTerbaru' => $jadwalTerbaru,
        ]);
    })->name('dashboard');

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
    });

    // ==========================================================
    // 4. Master - Skema (LOGIKA DIUBAH KEMBALI)
    // ==========================================================
    Route::controller(SkemaController::class)->prefix('master/skema')->group(function () {
        
        // DIPERBAIKI: Sekarang menunjuk ke 'index' (dengan logika search/sort)
        Route::get('/', 'index')->name('master_skema');
        
        // DIPERBAIKI: Sekarang menunjuk ke 'create'
        Route::get('/add', 'create')->name('add_skema');
        
        // Rute-rute ini sudah benar
        Route::post('/add', 'store')->name('add_skema.store');
        Route::get('/edit/{id_skema}', 'edit')->name('edit_skema');
        Route::patch('/update/{id_skema}', 'update')->name('update_skema');
        Route::delete('/delete/{id_skema}', 'destroy')->name('delete_skema');
    });
    // ==========================================================
    
    // 5. Master - Asesor
    
    // PERBAIKAN: Mengarahkan ke AsesorController@index agar Eager Loading, Search, dan Pagination berfungsi
    Route::get('/master_asesor', [AsesorController::class, 'index'])->name('master_asesor');
    
    // ... (Rute Add/Edit Asesor)
    Route::get('/add_asesor1', [AsesorController::class, 'createStep1'])->name('add_asesor1');
    Route::post('/add_asesor1', [AsesorController::class, 'postStep1'])->name('add_asesor1.post');
    Route::get('/add_asesor2', [AsesorController::class, 'createStep2'])->name('add_asesor2');
    Route::post('/add_asesor2', [AsesorController::class, 'postStep2'])->name('add_asesor2.post');
    Route::get('/add_asesor3', [AsesorController::class, 'createStep3'])->name('add_asesor3');
    
    // PERBAIKAN: Menyamakan path dengan rute yang lain agar konsisten
    Route::post('/asesor/store', [AsesorController::class, 'store'])->name('asesor.store');
    
    Route::get('/edit-asesor-step-1/{id_asesor}', [AsesorController::class, 'editStep1'])->name('edit_asesor1');
    Route::patch('/update-asesor-step-1/{id_asesor}', [AsesorController::class, 'updateStep1'])->name('asesor.update.step1');
    Route::get('/edit-asesor-step-2/{id_asesor}', [AsesorController::class, 'editStep2'])->name('edit_asesor2');
    Route::patch('/update-asesor-step-2/{id_asesor}', [AsesorController::class, 'updateStep2'])->name('asesor.update.step2');
    Route::get('/edit-asesor-step-3/{id_asesor}', [AsesorController::class, 'editStep3'])->name('edit_asesor3');
    Route::patch('/update-asesor-step-3/{id_asesor}', [AsesorController::class, 'updateStep3'])->name('asesor.update.step3');

    // ==========================================================
    // <!-- RUTE BARU UNTUK HAPUS ASESOR -->
    // ==========================================================
    // Rute ini menangani permintaan DELETE ke URL /asesor/{id_asesor}
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
    });
    Route::get('/schedule_admin', [ScheduleController::class, 'showCalendar'])->name('schedule_admin');

    // 8. TUK
    Route::controller(TukController::class)->prefix('master/tuk')->group(function () {
        Route::get('/', 'index')->name('master_tuk');           // Read (All)
        Route::get('/add', 'create')->name('add_tuk');          // Create (View)
        Route::post('/add', 'store')->name('add_tuk.store');    // Create (Save)
        Route::get('/edit/{id}', 'edit')->name('edit_tuk');       // Update (View)
        Route::patch('/update/{id}', 'update')->name('update_tuk');   // Update (Save)
        Route::delete('/delete/{id}', 'destroy')->name('delete_tuk'); // Delete
    });

    // 9. Profile Asesi
    Route::get('/asesi_profile_settings', function () { return view('profile_asesi.asesi_profile_settings'); })->name('asesi_profile_settings');
    Route::get('/asesi_profile_form', function () { return view('profile_asesi.asesi_profile_form'); })->name('asesi_profile_form');
    Route::get('/asesi_profile_bukti', function () { return view('profile_asesi.asesi_profile_bukti'); })->name('asesi_profile_bukti');
    Route::get('/asesi_profile_tracker', function () { return view('profile_asesi.asesi_profile_tracker'); })->name('asesi_profile_tracker');

    // 10. Profile Asesor
// PERUBAHAN: Rute 'asesor_profile_bukti' lama dihapus (diberi komentar)
    // Route::get('/asesor_profile_bukti', function () { return view('profile_asesor.asesor_profile_bukti'); })->name('asesor_profile_bukti');
    
    // RUTE BARU: Untuk menampilkan bukti kelengkapan asesor berdasarkan ID
    Route::get('/asesor/{id_asesor}/bukti', [AsesorController::class, 'showBukti'])->name('asesor.bukti');
    
    // PERUBAHAN: Rute 'asesor_profile_settings' lama dihapus (diberi komentar)
    // Route::get('/asesor_profile_settings', function () { return view('profile_asesor.asesor_profile_settings'); })->name('asesor_profile_settings');
    
    // RUTE BARU: Untuk menampilkan profil asesor berdasarkan ID
    Route::get('/asesor/{id_asesor}/profile', [AsesorController::class, 'showProfile'])->name('asesor.profile');

    Route::get('/asesor_profile_tinjauan', function () { return view('profile_asesor.asesor_profile_tinjauan'); })->name('asesor_profile_tinjauan');
    Route::get('/asesor_profile_tracker', function () { return view('profile_asesor.asesor_profile_tracker'); })->name('asesor_profile_tracker');
});

require __DIR__.'/auth.php';