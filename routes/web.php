<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController;
// use App\Http\Controllers\Asesor\FormIA07Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Bisa diakses siapa saja tanpa login)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetailPublik'])->name('jadwal.detail');
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal');

// Halaman Info & Profil
Route::get('/alur-sertifikasi', fn() => view('landing_page.page_info.alur-sertifikasi'))->name('info.alur');
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');
Route::get('/visimisi', fn() => view('landing_page.page_profil.visimisi'))->name('profil.visimisi');
Route::get('/struktur', fn() => view('landing_page.page_profil.struktur'))->name('profil.struktur');
Route::get('/mitra', fn() => view('landing_page.page_profil.mitra'))->name('profil.mitra');
Route::get('/sertifikasi', fn() => "Halaman Sertifikasi")->name('sertifikasi');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

// API Helper
Route::get('/keep-alive', fn() => response()->json(['status' => 'session_refreshed']));
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');


/*
|--------------------------------------------------------------------------
| 2. ROUTE AUTHENTICATED (Harus Login Dulu)
|--------------------------------------------------------------------------
| Semua route di sini otomatis kena middleware 'auth'.
| Di sini kita taruh FORM ASESMEN agar URL-nya tidak pakai '/asesor/'.
*/
Route::middleware(['auth'])->group(function () {

    // --- DASHBOARD UMUM ---
    Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');

    // --- PROFIL USER ---
    Route::post('/myprofil/asesor/update', [ProfileController::class, 'updateAsesorAjax'])->name('profil.asesor.update');
    Route::post('/myprofil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/myprofil', [ProfileController::class, 'show'])->name('profil.show');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR ASESMEN (Bisa diakses Asesi & Asesor)
    |--------------------------------------------------------------------------
    */
    
    // FR.IA.05 (Pertanyaan Tertulis)
    // Form A: Soal & Jawaban
    Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
    Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
    Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');

    // Form B: Kunci Jawaban
    Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
    Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');

    // Form C: Penilaian
    Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
    Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');

    // FR.IA.10
    Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    // Form Statis (Placeholder)
    Route::get('/FR-IA-10-view', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10');
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');

});


/*
|--------------------------------------------------------------------------
| 3. ROUTE KHUSUS ASESOR (Prefix: /asesor/...)
|--------------------------------------------------------------------------
| URL di sini akan otomatis depannya ada /asesor/
*/
Route::middleware(['auth'])->prefix('asesor')->group(function () {

    Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index');
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    
    // Daftar Asesi per Jadwal
    Route::get('/daftar-asesi/{id_jadwal}', [JadwalController::class, 'showAsesi'])->name('daftar_asesi');

    Route::get('/laporan', fn() => view('frontend.laporan'))->name('laporan');
    Route::get('/tracker', fn() => view('frontend.tracker'))->name('tracker');

});


/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Bawaan Laravel Breeze/Jetstream)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';