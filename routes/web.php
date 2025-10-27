<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/navbar', function () {
    return view('navbar-fix.blade.php');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::get('/home', [HomeController::class, 'home'])->name('home');
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal');
Route::get('/laporan', [HomeController::class, 'laporan'])->name('laporan');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';