<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// --------------------
// Halaman Umum (static page)
// --------------------
Route::get('/navbar', function () {
    return view('navbar-fix');
})->name('navbar');

Route::get('/daftar_asesi', function () {
    return view('frontend/daftar_asesi');
})->name('daftar_asesi');

Route::get('/tracker', function () {
    return view('frontend/tracker');
})->name('tracker');

// --------------------
// Halaman Home & Detail Skema
// --------------------

// Halaman utama (menampilkan semua skema)
// DIEDIT: Mengarah ke method 'home' (bukan 'index')
Route::get('/home', [HomeController::class, 'home'])->name('home');

// Halaman detail skema (klik dari Home)
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// DITAMBAHKAN: Route baru untuk menangani tombol Detail
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');


require __DIR__.'/auth.php';
