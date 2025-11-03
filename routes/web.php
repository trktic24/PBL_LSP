<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// --------------------
// Halaman Umum
// --------------------
Route::get('/navbar', function () {
    return view('navbar-fix');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/jadwal', function () {
    return view('jadwal');
})->name('jadwal');

Route::get('/laporan', function () {
    return view('laporan');
})->name('laporan');

Route::get('/profil', function () {
    return view('profil');
})->name('profil');

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