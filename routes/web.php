<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Http\Request;
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
    return view('frontend.profil');
})->name('profil');

// --------------------
// Halaman Home & Detail Skema
// --------------------

// Halaman utama (menampilkan semua skema)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});


require __DIR__.'/webprofil.php';
require __DIR__.'/auth.php';
