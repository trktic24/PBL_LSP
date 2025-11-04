<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SkemaController; 

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
| Rute Frontend (Publik)
| Folder: frontend
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('frontend.home');
})->name('home');

Route::get('/jadwal', function () {
    return view('frontend.jadwal');
})->name('jadwal');

Route::get('/laporan', function () {
    return view('frontend.laporan');
})->name('laporan');

Route::get('/profil', function () {
    return view('frontend.profil');
})->name('profil');

Route::get('/daftar_asesi', function () {
    return view('frontend.daftar_asesi');
})->name('daftar_asesi');

Route::get('/tracker', function () {
    return view('frontend.tracker');
})->name('tracker');


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
        return view('notification.notifications_admin');
    })->name('notifications');

    // 3. Profile Admin
    Route::get('/profile_admin', function () {
        return view('profile.profile_admin');
    })->name('profile_admin');

    // 4. Master - Skema
    Route::get('/master_skema', function () {
        return view('master.skema.master_skema');
    })->name('master_skema');
    Route::get('/add_skema', function () {
        return view('master.skema.add_skema');
    })->name('add_skema');
    Route::post('/add_skema', [SkemaController::class, 'store'])->name('add_skema.store');

    // 5. Master - Asesor
    Route::get('/master_asesor', function () {
        return view('master.asesor.master_asesor');
    })->name('master_asesor');
    Route::get('/add_asesor1', function () {
        return view('master.asesor.add_asesor1');
    })->name('add_asesor1');
    Route::get('/add_asesor2', function () {
        return view('master.asesor.add_asesor2');
    })->name('add_asesor2');
    Route::get('/add_asesor3', function () {
        return view('master.asesor.add_asesor3');
    })->name('add_asesor3');
    Route::post('/store-asesor', function () {
        return redirect()->route('master_asesor')->with('success', 'Asesor berhasil ditambahkan!');
    })->name('asesor.store');
    Route::get('/edit-asesor-step-1/{id}', function ($id) {
        return view('master.asesor.edit_asesor1', ['asesor' => (object)['id' => $id, 'user' => (object)['name' => 'Nama Tes', 'email' => 'email@tes.com']]]);
    })->name('edit_asesor1');
    Route::get('/edit-asesor-step-2/{id}', function ($id) {
        return view('master.asesor.edit_asesor2', ['asesor' => (object)['id' => $id]]);
    })->name('edit_asesor2');
    Route::get('/edit-asesor-step-3/{id}', function ($id) {
        return view('master.asesor.edit_asesor3', ['asesor' => (object)['id' => $id]]);
    })->name('edit_asesor3');
    Route::patch('/update-asesor/{id}', function ($id) {
        return redirect()->route('master_asesor')->with('success', 'Data Asesor berhasil diperbarui!');
    })->name('asesor.update');
    
    // 6. Master - Asesi
    Route::get('/master_asesi', function () {
        return view('master.asesi.master_asesi');
    })->name('master_asesi');
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
    Route::get('/tuk_sewaktu', function () {
        return view('tuk.tuk_sewaktu');
    })->name('tuk_sewaktu');
    Route::get('/add_tuk', function () {
        return view('tuk.add_tuk');
    })->name('add_tuk');
    Route::get('/tuk_tempatkerja', function () {
        return view('tuk.tuk_tempatkerja');
    })->name('tuk_tempatkerja');

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