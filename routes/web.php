<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\JadwalController; 
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\IA09Controller;

/*
|--------------------------------------------------------------------------
| Halaman Home & Detail Skema
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route resmi untuk detail skema
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');

// Tetap biarkan route /detail_skema/{id} jika masih dipakai di Blade lain
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Rute Detail Jadwal (FIX)
Route::get('/detail-jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');

// --------------------
// JADWAL ROUTES (PAKAI JadwalController)
// --------------------
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

// Halaman detail jadwal (redirect logic ada di controller)
Route::get('/jadwal/{id}', [JadwalController::class, 'show'])->name('jadwal.show');

// Halaman form pendaftaran peserta → landing_page.detail.detail_jadwal
Route::get('/jadwal/{id}/detail', [JadwalController::class, 'detail'])->name('jadwal.detail');

// DETAIL BERITA
Route::get('/berita/{id}', [HomeController::class, 'showBeritaDetail'])->name('berita.detail');

/*
|--------------------------------------------------------------------------
| Custom Routes (Info TUK dan Alur)
|--------------------------------------------------------------------------
*/
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');

Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

/*
|--------------------------------------------------------------------------
| Web Profil Routes
|--------------------------------------------------------------------------
*/
Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');

Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');

Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');

/*
|--------------------------------------------------------------------------
| Halaman Utama & Menu Utama (YANG PAKE CONTROLLER)
|--------------------------------------------------------------------------
*/

Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// ---
// FORM ASESMEN FR.IA.09 (Pertanyaan Wawancara)
// Route::view() digunakan untuk memuat view Blade sederhana.
// View path: resources/views/IA09/IA09_role.blade.php
// ---
Route::prefix('ia09')->group(function () {
    Route::get('/asesi/{id}', [IA09Controller::class, 'showAsesi'])->name('ia09.asesi');
    Route::get('/asesor/{id}', [IA09Controller::class, 'showAsesor'])->name('ia09.asesor');
    Route::get('/admin/{id}', [IA09Controller::class, 'showAdmin'])->name('ia09.admin');

    Route::put('/asesor/{id}', [IA09Controller::class, 'updateAsesor'])->name('ia09.asesor.update');
});

/*
|--------------------------------------------------------------------------
| API & Keep Alive
|--------------------------------------------------------------------------
*/
Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});
Route::get('/api/search-countries', [CountryController::class, 'search'])
    ->name('api.countries.search');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';