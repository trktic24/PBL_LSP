<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormIa10Controller;

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
Route::get('/FR-IA-10', function () {
    return view('frontend/FR_IA_10');
})->name('FR-IA-10');
Route::get('/fr-ia-06-c', function () {
    return view('frontend/fr_IA_06_c');
})->name('fr_IA_06_c');
Route::get('/fr-ia-06-a', function () {
    return view('frontend/fr_IA_06_a');
})->name('fr_IA_06_a');
Route::get('/fr-ia-06-b', function () {
    return view('frontend/fr_IA_06_b');
})->name('fr_IA_06_b');
Route::get('/fr-ia-07', function () {
    return view('frontend/FR_IA_07');
})->name('FR_IA_07');
Route::get('/fr-ia-05-a', function () {
    return view('frontend/FR_IA_05_A');
})->name('FR_IA_05_A');
Route::get('/fr-ia-05-b', function () {
    return view('frontend/FR_IA_05_B');
})->name('FR_IA_05_B');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/fr-ia-10', [FormIa10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [FormIa10Controller::class, 'store'])->name('fr-ia-10.store');
});

require __DIR__.'/auth.php';
