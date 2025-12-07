<?php

use Illuminate\Support\Facades\Route;

// --- Controllers Import ---
use App\Http\Controllers\TukController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\AsesorController; 
use App\Http\Controllers\AsesiController; 
use App\Http\Controllers\TukAdminController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Api\V1\MitraController;
use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\HomeController;


// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail Skema
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

// Info TUK & Alur & Profil
Route::get('/alur-sertifikasi', fn() => view('landing_page.page_info.alur-sertifikasi'))->name('info.alur');
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

Route::get('/mitra', [MitraController::class, 'index'])->name('profil.mitra');

/*
|--------------------------------------------------------------------------
| Halaman Utama & Menu Utama (YANG PAKE CONTROLLER)
|--------------------------------------------------------------------------
*/

Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

Route::get('/sertifikasi', fn() => "Halaman Sertifikasi")->name('sertifikasi');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// Register User Baru
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// API Helper & Keep Alive
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');


/*
|--------------------------------------------------------------------------
| 4. AUTH ROUTES (Bawaan Laravel Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

// ====================================================
// 4. API & UTILITIES (NON-AUTH / MIXED)
// ====================================================

// Keep Alive Session
Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});

// API Wilayah
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');

Route::post('/api/jadwal/daftar', [TrackerController::class, 'daftarJadwal'])->name('api.jadwal.daftar');