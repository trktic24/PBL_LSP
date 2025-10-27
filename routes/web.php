<?php

# use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/navbar', function () {
    return view('navbar-fix.blade.php');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');
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

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'edit'])->name('profile.edit');
    Route::patch('/home', [HomeController::class, 'update'])->name('profile.update');
    Route::delete('/home', [HomeController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
