<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController;



/*
|--------------------------------------------------------------------------
| ROUTE INTERNAL APLIKASI (ASESOR)
|--------------------------------------------------------------------------
|
| Semua route internal Asesor harus ada di dalam grup middleware 'auth'
| agar hanya pengguna yang sudah login bisa mengakses.
|
*/

Route::middleware('auth')->group(function () {

    /**
     * DASHBOARD UTAMA ASESOR
     */
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /**
     * JADWAL ASESMEN
     */
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    /**
     * LAPORAN ASESMEN
     */
    Route::get('/laporan', function () {
        return view('frontend.laporan');
    })->name('laporan');

    /**
     * PROFIL USER LOGIN (DIPERBARUI)
     * Sekarang memanggil controller agar data profil sesuai dengan user login.
     */

    Route::post('/myprofil/asesor/update', [ProfileController::class, 'updateAsesorAjax'])
     ->name('profil.asesor.update') // Kita gunakan nama baru: 'profil.asesor.update'
     ->middleware('auth');
    Route::post('/myprofil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/myprofil', [ProfileController::class, 'show'])->name('profil.show');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil')->middleware('auth');


    /**
     * DAFTAR ASESI (per jadwal)
     */
    Route::get('/daftar_asesi/{id}', function ($id) {
        return view('frontend.daftar_asesi', ['jadwal_id' => $id]);
    })->name('daftar_asesi');

    /**
     * TRACKER ASESMEN
     */
    Route::get('/tracker', function () {
        return view('frontend.tracker');
    })->name('tracker');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR ASESMEN
    |--------------------------------------------------------------------------
    */
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR STATIS (opsional, untuk tampilan non-dinamis)
    |--------------------------------------------------------------------------
    */
    Route::get('/FR-IA-10', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10');
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');
    Route::get('/fr-ia-05-a', fn() => view('frontend.FR_IA_05_A'))->name('FR_IA_05_A');
    Route::get('/fr-ia-05-b', fn() => view('frontend.FR_IA_05_B'))->name('FR_IA_05_B');
    Route::get('/fr-ia-05-c', fn() => view('frontend.FR_IA_05_C'))->name('FR_IA_05_C');
    Route::get('/fr-ia-02', fn() => view('frontend.FR_IA_02'))->name('FR_IA_02');

    // Tidak perlu route /profile dan /dashboard lagi, sudah di auth.php
});

/*
|--------------------------------------------------------------------------
| ROUTE AUTENTIKASI (LOGIN, REGISTER, LOGOUT)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
