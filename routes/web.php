<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Asesor\FormIA07Controller;

use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\SkemaWebController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;

/*
|--------------------------------------------------------------------------
| Halaman Home & Detail Skema (PUBLIK)
|--------------------------------------------------------------------------
*/
// Ini adalah 'home' untuk landing page publik
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route resmi untuk detail skema
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');

// Tetap biarkan route /detail_skema/{id} jika masih dipakai di Blade lain
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Rute Detail Jadwal
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');

// --- DIPERBAIKI ---
// Rute ini sekarang mengarah ke fungsi publik baru 'showJadwalDetailPublik'
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetailPublik'])->name('jadwal.detail');


/*
|--------------------------------------------------------------------------
| Custom Routes (Info TUK dan Alur) (PUBLIK)
|--------------------------------------------------------------------------
*/
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');

Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

/*
|--------------------------------------------------------------------------
| Web Profil Routes (PUBLIK)
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
| Halaman Utama & Menu Utama (PUBLIK)
|--------------------------------------------------------------------------
*/
// --- DIPERBAIKI ---
// Rute ini sekarang mengarah ke fungsi 'jadwal' di HomeController, BUKAN JadwalController
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal');

// Note: Rute 'register' ini mungkin perlu GET, tapi saya biarkan sesuai file asli
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');


/*
|--------------------------------------------------------------------------
| ROUTE INTERNAL APLIKASI (ASESOR)
|--------------------------------------------------------------------------
|
| Grup ini sudah benar (menggunakan prefix 'asesor')
|
*/
Route::middleware('auth')->prefix('asesor')->group(function () {

    /**
     * JADWAL ASESMEN (INTERNAL)
     * URL otomatis menjadi: /asesor/jadwal
     * Ini memanggil JadwalController, yang benar untuk asesor.
     */
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');
    Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index');

    /**
     * LAPORAN ASESMEN (INTERNAL)
     * URL otomatis menjadi: /asesor/laporan
     */
    Route::get('/laporan', function () {
        return view('frontend.laporan');
    })->name('laporan');

    /**
     * PROFIL USER LOGIN (INTERNAL)
     * URL otomatis menjadi: /asesor/myprofil, /asesor/profil, dll.
     */
    Route::post('/myprofil/asesor/update', [ProfileController::class, 'updateAsesorAjax'])
     ->name('profil.asesor.update')
     ->middleware('auth');
    Route::post('/myprofil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/myprofil', [ProfileController::class, 'show'])->name('profil.show');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil')->middleware('auth');


    /**
     * DAFTAR ASESI (per jadwal)
     * URL otomatis menjadi: /asesor/daftar-asesi/{id_jadwal}
     */
    Route::get('/daftar-asesi/{id_jadwal}', [JadwalController::class, 'showAsesi'])
         ->name('daftar_asesi');

    /**
     * TRACKER ASESMEN
     * URL otomatis menjadi: /asesor/tracker
     */
    Route::get('/tracker', function () {
        return view('frontend.tracker');
    })->name('tracker');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR ASESMEN (INTERNAL)
    |--------------------------------------------------------------------------
    */
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');

    /*
    |--------------------------------------------------------------------------
    | FORMULIR STATIS (INTERNAL)
    |--------------------------------------------------------------------------
    */
    Route::get('/FR-IA-10', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10');
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');
    Route::get('/fr-ia-05-a', fn() => view('frontend.FR_IA_05_A'))->name('FR_IA_05_A');
    Route::get('/fr-ia-05-b', fn() => view('frontend.FR_IA_05_B'))->name('FR_IA_05_B');
    Route::get('/fr-ia-05-c', fn() => view('frontend.FR_IA_05_C'))->name('FR_IA_05_C');
    Route::get('/fr-ia-02', fn() => view('frontend.FR_IA_02'))->name('FR_IA_02');

});

/*
|--------------------------------------------------------------------------
| ROUTE AUTENTIKASI (LOGIN, REGISTER, LOGOUT)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

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
// require __DIR__.'/auth.php'; // Ini duplikat, sudah ada di atas