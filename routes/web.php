<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\IA01Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\PortofolioController;

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
Route::get('/FR_AK_02', function () {
    return view('frontend/FR_AK_02');
})->name('FR_AK_02');
Route::get('/FR_AK_03', function () {
    return view('frontend/FR_AK_03');
})->name('FR_AK_03');
Route::get('/FR_AK_04', function () {
    return view('frontend/FR_AK_04');
})->name('FR_AK_04');
Route::get('/FR_AK_05', function () {
    return view('frontend/FR_AK_05');
})->name('FR_AK_05');


Route::get('/IA_08', function () {
    return view('frontend/IA_08/IA_08');
})->name('IA08');
// FR IA 11
Route::get('/FR_IA_11', function () {
    return view('frontend/FR_IA_11');
})->name('FR_IA_11');

//porto
Route::get('/PORTOFOLIO', [PortofolioController::class, 'index'])->name('PORTOFOLIO');
// Route untuk menyimpan data upload
Route::post('/PORTOFOLIO', [PortofolioController::class, 'store'])->name('portofolio.store');

//IA07
Route::get('/FR_IA_07', [IA07Controller::class, 'index'])->name('ia07.asesor');
Route::post('/FR_IA_07/store', [IA07Controller::class, 'store'])->name('ia07.store');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/ia02-test', function() {
        return "Route IA02 Terhubung! Silakan akses dengan ID, misal: /ia02/1";
    });

    // Route Utama (Memerlukan ID)
    Route::get('/ia02/{id_sertifikasi}', [IA02Controller::class, 'show'])->name('ia02.show');
    Route::post('/ia02/{id_sertifikasi}', [IA02Controller::class, 'store'])->name('ia02.store');
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
