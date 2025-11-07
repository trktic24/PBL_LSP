<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;



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

// Forms Frontend Routes
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
Route::get('/fr-ia-05-c', function () {
    return view('frontend/FR_IA_05_C');
})->name('FR_IA_05_C');

Route::get('/fr-ia-02', function () {
    return view('frontend/FR_IA_02');
})->name('FR_IA_02');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])

    ->middleware('auth')
    ->middleware('auth') //
    ->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])
    ->middleware('auth')
    ->name('fr-ia-10.store');
    Route::get('/dashboard', function () {return view('dashboard'); })->middleware(['auth'])->name('dashboard');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])
         ->name('fr-ia-02.show');

    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])
         ->name('fr-ia-02.store');
});

Route::resource('jadwal', JadwalController::class);

require __DIR__.'/auth.php';
