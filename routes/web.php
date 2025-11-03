<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;

// --------------------
// Halaman Umum (static page)
// --------------------
Route::get('/navbar', function () {
    return view('navbar-fix');
})->name('navbar');

Route::get('/daftar_asesi', function () {
    return view('frontend/daftar_asesi');
})->name('daftar_asesi');

Route::get('/tracker', function () {
    return view('frontend/tracker');
})->name('tracker');

// --------------------
// Halaman Home & Detail Skema
// --------------------

Route::get('/', [HomeController::class, 'index'])->name('home');
// Halaman detail skema (klik dari Home)
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// DITAMBAHKAN: Route baru untuk menangani tombol Detail
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');


/*
|--------------------------------------------------------------------------
| Custom Routes (Info TUK dan Alur)
|--------------------------------------------------------------------------
*/
// Rute untuk Detail Skema (TETAP CLOSURE)
Route::get('/detail_skema', function () {
    return view('landing_page.detail.detail_skema');
})->name('skema.detail');

// Rute untuk alur sertifikasi (TETAP CLOSURE)
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');

// Rute untuk info TUK (Daftar) - MENGGUNAKAN TukController@index
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');

// Rute untuk detail TUK - MENGGUNAKAN TukController@showDetail DENGAN PARAMETER DINAMIS {id}
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');


/*
|--------------------------------------------------------------------------
| Web Profil Routes
|--------------------------------------------------------------------------
*/
// Rute untuk Visi & Misi (TETAP CLOSURE)
Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');

// Rute untuk Struktur (TETAP CLOSURE)
Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');

// Rute untuk Mitra (TETAP CLOSURE)
Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');

/*
|--------------------------------------------------------------------------
| Halaman Utama & Menu Utama
|--------------------------------------------------------------------------
*/

Route::get('/jadwal', function () {
    return view('landing_page.jadwal');
})->name('jadwal');

// Rute untuk Sertifikasi (TETAP CLOSURE)
Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

// Rute untuk Daftar Asesor (TETAP CLOSURE)
Route::get('/daftar-asesor', function () {
    return view('landing_page.page_info.daftar-asesor');
})->name('info.daftar-asesor');

// (TETAP CLOSURE)
Route::get('/detail_jadwal', function () {
    return view('landing_page.detail.detail_jadwal');
});

// require __DIR__.'/auth.php';


require __DIR__.'/auth.php';
