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

// GET / : Tampilkan halaman form login
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');

// POST /login : Proses data login dari form
Route::post('/login', [LoginController::class, 'login']);

// POST /logout : Proses logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| Rute Frontend (Publik)
| (Ini dari branch 'Database' Anda)
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return view('frontend/home');
})->name('home');

Route::get('/jadwal', function () {
    return view('frontend/jadwal');
})->name('jadwal');

Route::get('/laporan', function () {
    return view('frontend/laporan');
})->name('laporan');

Route::get('/profil', function () {
    return view('frontend/profil');
})->name('profil');

Route::get('/daftar_asesi', function () {
    return view('frontend/daftar_asesi');
})->name('daftar_asesi');

Route::get('/tracker', function () {
    return view('frontend/tracker');
})->name('tracker');


/*
|--------------------------------------------------------------------------
| Rute Panel Admin (Semua di sini WAJIB LOGIN)
| (Ini dari branch 'Kelompok_4' Anda)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard
    // Ubah /dashboard_admin menjadi /dashboard agar sesuai dengan LoginController
    Route::get('/dashboard', function () {
        return view('dashboard_admin');
    })->name('dashboard');

    // Notifikasi
    Route::get('/notifications', function () {
        return view('notifications_admin');
    })->name('notifications');

    // Profil Admin
    Route::get('/profile_admin', function () {
        return view('profile_admin'); 
    })->name('profile_admin');

    // Master Skema
    Route::get('/master_skema', function () {
        return view('master_skema'); 
    })->name('master_skema');

    Route::get('/add_skema', function () {
        return view('add_skema');
    })->name('add_skema');

    // Master Asesi
    Route::get('/master_asesi', function () {
        return view('master_asesi'); 
    })->name('master_asesi');

    Route::get('/add-asesi-step-1', function () {
        return view('add_asesi1'); 
    })->name('add_asesi1');

    Route::get('/add-asesi-step-2', function () {
        return view('add_asesi2'); 
    })->name('add_asesi2');

    Route::get('/add-asesi-step-3', function () {
        return view('add_asesi3'); 
    })->name('add_asesi3');

    Route::get('/add-asesi-step-4', function () {
        return view('add_asesi4'); 
    })->name('add_asesi4');

    // Rute untuk memproses data
    Route::post('/store-asesi', function () {
        // Logika untuk menyimpan data asesi
        return redirect()->route('master_asesi')->with('success', 'Asesi berhasil ditambahkan!');
    })->name('asesi.store');

    // Master Asesor
    Route::get('/master_asesor', function () {
        return view('master_asesor'); 
    })->name('master_asesor');

    Route::get('/add_asesor1', function () {
        return view('add_asesor1'); 
    })->name('add_asesor1');

    Route::get('/add_asesor2', function () {
        return view('add_asesor2'); 
    })->name('add_asesor2');

    Route::get('/add_asesor3', function () {
        return view('add_asesor3'); 
    })->name('add_asesor3');

    // Rute untuk MENYIMPAN data form
    Route::post('/add_skema', [SkemaController::class, 'store'])->name('add_skema.store');

    //Rute untuk menyimpan data belum ada, untuk saat ini dibuat rute dummy jika bellum ada controller
    Route::post('/store-asesor', function () {
        return redirect()->route('master_asesor')->with('success', 'Asesor berhasil ditambahkan!');
    })->name('asesor.store');

    // Halaman Edit Asesor Step 1 (Informasi Akun)
    // Perhatikan {id} untuk mengambil data asesor
    Route::get('/edit-asesor-step-1/{id}', function ($id) {
        // Di sini Anda perlu Controller untuk mengambil data Asesor
        // $asesor = Asesor::findOrFail($id);
        // return view('edit_asesor1', ['asesor' => $asesor]);
        
        // Untuk sementara jika belum ada Controller:
        return view('edit_asesor1', ['asesor' => (object)['id' => $id, 'user' => (object)['name' => 'Nama Tes', 'email' => 'email@tes.com']]]); // Hapus ini nanti
    })->name('edit_asesor1');

    // Halaman Edit Asesor Step 2 (Data Pribadi)
    Route::get('/edit-asesor-step-2/{id}', function ($id) {
        // $asesor = Asesor::findOrFail($id);
        // return view('edit_asesor2', ['asesor' => $asesor]);
        
        // Untuk sementara:
        return view('edit_asesor2', ['asesor' => (object)['id' => $id]]); // Hapus ini nanti
    })->name('edit_asesor2');

    // Halaman Edit Asesor Step 3 (Kelengkapan Dokumen)
    Route::get('/edit-asesor-step-3/{id}', function ($id) {
        // $asesor = Asesor::findOrFail($id);
        // return view('edit_asesor3', ['asesor' => $asesor]);
        
        // Untuk sementara:
        return view('edit_asesor3', ['asesor' => (object)['id' => $id]]); // Hapus ini nanti
    })->name('edit_asesor3');

    // Rute untuk MENYIMPAN PERUBAHAN (dari tombol "Simpan" di step 3)
    Route::patch('/update-asesor/{id}', function ($id) {
        // Logika untuk validasi dan update data ke database
        return redirect()->route('master_asesor')->with('success', 'Data Asesor berhasil diperbarui!');
    })->name('asesor.update');

    // TUK sewaktu
    Route::get('/tuk_sewaktu', function () {
        return view('tuk_sewaktu'); 
    })->name('tuk_sewaktu');

    // Add TUK
    Route::get('/add_tuk', function () {
        return view('add_tuk'); 
    })->name('add_tuk');

    // TUK Tempat Kerja
    Route::get('/tuk_tempatkerja', function () {
        return view('tuk_tempatkerja'); 
    })->name('tuk_tempatkerja');

    // Schedule
    Route::get('/schedule_admin', function () {
        return view('schedule_admin'); 
    })->name('schedule_admin');

    // Master_Schedule
    Route::get('/master_schedule', function () {
        return view('master_schedule'); 
    })->name('master_schedule');

    // Add Schedule
    Route::get('/add_schedule', function () {
        return view('add_schedule'); 
    })->name('add_schedule');

    // Edit Schedule
    Route::get('/edit_schedule', function () {
        return view('edit_schedule'); 
    })->name('edit_schedule');

    // asesi Profile Settings
    Route::get('/asesi_profile_settings', function () {
        return view('asesi_profile_settings'); 
    })->name('asesi_profile_settings');

    // asesi Profile Form
    Route::get('/asesi_profile_form', function () {
        return view('asesi_profile_form'); 
    })->name('asesi_profile_form');

    // asesi Profile Bukti
    Route::get('/asesi_profile_bukti', function () {
        return view('asesi_profile_bukti'); 
    })->name('asesi_profile_bukti');

    // Asesi Profile Tracker
    Route::get('/asesi_profile_tracker', function () {
        return view('asesi_profile_tracker'); 
    })->name('asesi_profile_tracker');

    // Asesor Profile Bukti
    Route::get('/asesor_profile_bukti', function () {
        return view('asesor_profile_bukti'); 
    })->name('asesor_profile_bukti');

    // Asesor Profile Settings
    Route::get('/asesor_profile_settings', function () {
        return view('asesor_profile_settings'); 
    })->name('asesor_profile_settings');

    // Asesor Profile Tinjauan
    Route::get('/asesor_profile_tinjauan', function () {
        return view('asesor_profile_tinjauan'); 
    })->name('asesor_profile_tinjauan');

    // Asesor Profile Tracker
    Route::get('/asesor_profile_tracker', function () {
        return view('asesor_profile_tracker'); 
    })->name('asesor_profile_tracker');

    // route profil bawaan Laravel
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';