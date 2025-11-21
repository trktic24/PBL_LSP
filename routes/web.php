<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\Ia01Controller;
use App\Http\Controllers\IA07Controller;

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

//APL
Route::get('/APL_01_1', function () {
    return view('frontend/APL_01/APL_01_1');
})->name('APL_01_1');
Route::get('/APL_01_2', function () {
    return view('frontend/APL_01/APL_01_2');
})->name('APL_01_2');
Route::get('/APL_02', function () {
    return view('frontend/APL_02/APL_02');
})->name('APL_02');

// MAPA
Route::get('/FR-MAPA-02', function () {
    return view('frontend/FR_MAPA_02');
})->name('MAPA-02');

//AK
Route::get('/FR_AK_01', function () {
    return view('frontend/FR_AK_01');
})->name('FR_AK_01');
Route::get('/FR_AK_04', function () {
    return view('frontend/FR_AK_04');
})->name('FR_AK_04');

// Akses untuk Asesor: http://localhost:8000/IA07_Asesor
Route::get('/IA07_Asesor', [IA07Controller::class, 'index'])->name('ia07.asesor');

// Akses untuk Asesi: http://localhost:8000/IA07_Asesi
Route::get('/IA07_Asesi', [IA07Controller::class, 'index'])->name('ia07.asesi');

// Route POST (store)
Route::post('/ia07/store', [IA07Controller::class, 'store'])->name('ia07.store');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/soal', [SoalController::class, 'index'])->name('soal.index');
Route::get('/soal/create', [SoalController::class, 'create'])->name('soal.create');
Route::post('/soal', [SoalController::class, 'store'])->name('soal.store');
Route::get('/soal/{id}/edit', [SoalController::class, 'edit'])->name('soal.edit');
Route::put('/soal/{id}', [SoalController::class, 'update'])->name('soal.update');
Route::delete('/soal/{id}', [SoalController::class, 'destroy'])->name('soal.destroy');
Route::get('/onlysoal', [SoalController::class, 'onlySoal'])->name('soal.only');

// CRUD kunci jawaban
Route::post('/soal/{id}/kunci', [SoalController::class, 'storeKunci'])->name('kunci.store');
Route::put('/kunci/{id}', [SoalController::class, 'updateKunci'])->name('kunci.update');
Route::delete('/kunci/{id}', [SoalController::class, 'destroyKunci'])->name('kunci.destroy');

// Menampilkan halaman menjawab soal
Route::get('/jawab', [SoalController::class, 'jawabIndex'])->name('jawab.index');

// Menyimpan jawaban user
Route::post('/jawab', [SoalController::class, 'jawabStore'])->name('jawab.store');

//IA 01
// Route Halaman Awal (Cover)
Route::get('/ia01/{skema_id}/cover', [Ia01Controller::class, 'showCover'])->name('ia01.cover');
Route::post('/ia01/{skema_id}/cover', [Ia01Controller::class, 'storeCover'])->name('ia01.storeCover');
// Route Step Wizard (Unit 1, 2, dst)
Route::get('/ia01/{skema_id}/step/{urutan}', [Ia01Controller::class, 'showStep'])->name('ia01.showStep');
Route::post('/ia01/{skema_id}/step/{urutan}', [Ia01Controller::class, 'storeStep'])->name('ia01.storeStep');

require __DIR__.'/auth.php';
