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
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Halaman detail skema (klik dari Home)
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

require __DIR__.'/auth.php';
