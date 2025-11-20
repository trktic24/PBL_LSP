<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\IA01Controller;

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
Route::get('/APL_01_3', function () {
    return view('frontend/APL_01/APL_01_3');
})->name('APL_01_3');
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

//IA
Route::get('/IA_07', function () {
    return view('frontend/IA_07/IA_07');
})->name('IA07');
Route::get('/IA_08', function () {
    return view('frontend/IA_08/IA_08');
})->name('IA08');


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
Route::get('/ia01/{skema_id}/cover', [IA01Controller::class, 'showCover'])->name('ia01.cover');
Route::post('/ia01/{skema_id}/cover', [IA01Controller::class, 'storeCover'])->name('ia01.storeCover');
// Route Step Wizard (Unit 1, 2, dst)
Route::get('/ia01/{skema_id}/step/{urutan}', [IA01Controller::class, 'showStep'])->name('ia01.showStep');
Route::post('/ia01/{skema_id}/step/{urutan}', [IA01Controller::class, 'storeStep'])->name('ia01.storeStep');
Route::get('/ia01/{skema_id}/finish', [IA01Controller::class, 'showFinish'])->name('ia01.finish');
Route::post('/ia01/{skema_id}/finish', [IA01Controller::class, 'storeFinish'])->name('ia01.storeFinish');
// Route Halaman Sukses (Image 2)
Route::get('/ia01/success', function() {
    return view('frontend.IA_01.success');
})->name('ia01.success_page');
require __DIR__.'/auth.php';
