<?php

<<<<<<< HEAD
=======
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
>>>>>>> 1ad5aa7b95f7955bc9d188e541de9c29b641a319
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// --------------------
// Halaman Umum
// --------------------
Route::get('/navbar', function () {
    return view('navbar-fix'); // cukup nama filenya, tanpa .blade.php
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

<<<<<<< HEAD
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
=======

Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal');
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
>>>>>>> 1ad5aa7b95f7955bc9d188e541de9c29b641a319


require __DIR__.'/auth.php';