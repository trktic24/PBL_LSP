<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Api\CountryController;

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

// Rute Detail Jadwal
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');

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
| Halaman Utama & Menu Utama
|--------------------------------------------------------------------------
*/
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');

Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

/*
|--------------------------------------------------------------------------
| Keep Alive
|--------------------------------------------------------------------------
*/
Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});
Route::get('/api/search-countries', [CountryController::class, 'search'])
      ->name('api.countries.search');


require __DIR__.'/auth.php';
