<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\V1\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\AsesorController;
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\Ia06Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\FrMapa01Controller;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Landing Page, Info, Jadwal Umum)
|--------------------------------------------------------------------------
*/

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

Route::get('/sertifikasi', fn() => "Halaman Sertifikasi")->name('sertifikasi');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

/*
|--------------------------------------------------------------------------
| ASESOR ROUTES
|--------------------------------------------------------------------------
*/


// Register User Baru
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

// API Helper & Keep Alive
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');


//ROUTES PDF
Route::get('/mapa02/cetak/{id}', [Mapa02Controller::class, 'cetakPDF'])->name('mapa02.cetak_pdf');
Route::get('/ia05/cetak/{id_asesi}', [IA05Controller::class, 'cetakPDF'])->name('ia05.cetak_pdf');
Route::get('/ia10/cetak/{id_asesi}', [IA10Controller::class, 'cetakPDF'])->name('ia10.cetak_pdf');
Route::get('/ia02/cetak/{id}', [IA02Controller::class, 'cetakPDF'])->name('ia02.cetak_pdf');
Route::get('/ia06/cetak/{id}', [Ia06Controller::class, 'cetakPDF'])->name('ia06.cetak_pdf');
Route::get('/ia07/cetak/{id}', [IA07Controller::class, 'cetakPDF'])->name('ia07.cetak_pdf');
Route::get('/apl01/cetak/{id}', [APL01Controller::class, 'cetakPDF'])->name('apl01.cetak_pdf');
Route::get('/mapa01/cetak/{id}', [FrMapa01Controller::class, 'cetakPDF'])->name('mapa01.cetak_pdf');
Route::get('/apl02/cetak/{id}', [PraasesmenController::class, 'generatePDF'])->name('apl02.cetak_pdf');
Route::get('/ak01/cetak/{id}', [PersetujuanKerahasiaanAPIController::class, 'cetakPDF'])->name('ak01.cetak_pdf');


/*
|--------------------------------------------------------------------------
| 4. AUTH ROUTES (Bawaan Laravel Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
