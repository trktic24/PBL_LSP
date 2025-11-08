<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\ProfileController; // Diperlukan oleh auth.php
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController; // Controller Anda untuk dashboard home

/*
|--------------------------------------------------------------------------
| ROUTE INTERNAL APLIKASI (ASESOR)
|--------------------------------------------------------------------------
|
| SEMUA route internal Asesor harus ada di dalam grup middleware 'auth'
| untuk memastikan hanya Asesor yang login yang bisa mengakses.
|
*/

Route::middleware('auth')->group(function () {

    /**
     * Rute Dashboard Utama Asesor
     * GET /home -> HomeController@index -> view('frontend.home')
     * Ini memanggil HomeController Anda yang sudah benar.
     */
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    /**
     * Rute Halaman Jadwal Asesor
     * GET /jadwal -> JadwalController@index -> view('frontend.jadwal', compact('jadwals'))
     * Ini memanggil JadwalController Anda dan mengirim variabel $jadwals.
     * Ini akan memperbaiki error 'Undefined variable $jadwals'.
     * Nama 'jadwal' cocok dengan navbar Anda.
     */
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

    // CATATAN: Kita tidak pakai Route::resource('jadwal') karena itu
    // akan membuat nama 'jadwal.index' yang tidak cocok dengan navbar Anda.

    /**
     * Rute Halaman Lainnya
     * Ini memperbaiki error 'Route [laporan] not defined'.
     */
    Route::get('/laporan', function () {
        return view('frontend/laporan');
    })->name('laporan');

    Route::get('/profil', function () {
        return view('frontend/profil');
    })->name('profil');

    /**
     * Rute Daftar Asesi (Perbaikan Kritis)
     * DIPERBAIKI: Menambahkan parameter {id}
     * Ini akan memperbaiki error RouteNotFound saat menekan tombol 'Lihat'
     * di halaman frontend/jadwal.blade.php
     */
    Route::get('/daftar_asesi/{id}', function ($id) {
        // TODO: Ambil data asesi berdasarkan $id jadwal
        return view('frontend/daftar_asesi', ['jadwal_id' => $id]);
    })->name('daftar_asesi');

    Route::get('/tracker', function () {
        return view('frontend/tracker');
    })->name('tracker');


    /*
    |--------------------------------------------------------------------------
    | Rute Formulir Asesmen
    |--------------------------------------------------------------------------
    */

    // DIPERBAIKI: Menghapus duplikasi route /fr-ia-10
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');


    /*
    |--------------------------------------------------------------------------
    | Rute Formulir Statis (jika masih dipakai)
    |--------------------------------------------------------------------------
    */
    Route::get('/FR-IA-10', function () { return view('frontend/FR_IA_10'); })->name('FR-IA-10');
    Route::get('/fr-ia-06-c', function () { return view('frontend/fr_IA_06_c'); })->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', function () { return view('frontend/fr_IA_06_a'); })->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', function () { return view('frontend/fr_IA_06_b'); })->name('fr_IA_06_b');
    Route::get('/fr-ia-07', function () { return view('frontend/FR_IA_07'); })->name('FR_IA_07');
    Route::get('/fr-ia-05-a', function () { return view('frontend/FR_IA_05_A'); })->name('FR_IA_05_A');
    Route::get('/fr-ia-05-b', function () { return view('frontend/FR_IA_05_B'); })->name('FR_IA_05_B');
    Route::get('/fr-ia-05-c', function () { return view('frontend/FR_IA_05_C'); })->name('FR_IA_05_C');
    Route::get('/fr-ia-02', function () { return view('frontend/FR_IA_02'); })->name('FR_IA_02');

    // DIHAPUS: Route /profile dan /dashboard karena sudah ada di auth.php
});

/*
|--------------------------------------------------------------------------
| Route Autentikasi
|--------------------------------------------------------------------------
*/
// File ini (auth.php) sudah menangani /login, /register, dan /dashboard (logika redirect)
require __DIR__.'/auth.php';