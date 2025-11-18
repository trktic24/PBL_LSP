<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Asesor\FormIA07Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\SkemaWebController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Auth\RegisteredUserController; // Pastikan ini diimport

/*
|--------------------------------------------------------------------------
| 1. HALAMAN PUBLIK (Landing Page, Info, Jadwal Umum)
|--------------------------------------------------------------------------
*/

// Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Detail Skema
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Jadwal Publik (List & Detail)
Route::get('/jadwal', [HomeController::class, 'jadwal'])->name('jadwal'); // <-- List Jadwal Publik
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetailPublik'])->name('jadwal.detail'); // <-- Detail Jadwal Publik

// Info TUK & Alur
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');

Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

// Profil Web (Visi Misi, dll)
Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');

Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');

Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');

// Register User Baru
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');


/*
|--------------------------------------------------------------------------
| 2. ROUTE INTERNAL ASESOR (Dilindungi Auth & Prefix /asesor)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->prefix('asesor')->group(function () {

    // Dashboard Asesor (/asesor/home)
    Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index');

    // Jadwal Internal (/asesor/jadwal) - Menggunakan JadwalController
    Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal.index');

    // Laporan
    Route::get('/laporan', function () {
        return view('frontend.laporan');
    })->name('laporan');

    // Profil Internal (Edit Profil Asesor)
    Route::post('/myprofil/asesor/update', [ProfileController::class, 'updateAsesorAjax'])->name('profil.asesor.update');
    Route::post('/myprofil/update', [ProfileController::class, 'update'])->name('profil.update');
    Route::get('/myprofil', [ProfileController::class, 'show'])->name('profil.show');
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profil');

    // Daftar Asesi
    Route::get('/daftar-asesi/{id_jadwal}', [JadwalController::class, 'showAsesi'])->name('daftar_asesi');

    // Tracker
    Route::get('/tracker', function () {
        return view('frontend.tracker');
    })->name('tracker');

    /*
     * FORMULIR ASESMEN (IA-02, IA-10, dll)
     */
    Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');

    /*
     * FORMULIR STATIS
     */
    Route::get('/FR-IA-10', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10');
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');
    Route::get('/fr-ia-02', fn() => view('frontend.FR_IA_02'))->name('FR_IA_02');

    /*
     * FORMULIR IA-05 (Kompleks dengan Role)
     */
    // Form A
    Route::middleware(['role:admin,asesor,asesi'])->group(function () {
        Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
    });
    Route::middleware(['role:asesi'])->group(function () {
        Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');
    });

    // Form B
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');
    });

    // Form C
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
    });
    Route::middleware(['role:asesor'])->group(function () {
        Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');
    });

}); // <--- ⛔ PENTING: Penutup grup Asesor harus DI SINI, sebelum auth.php

/*
|--------------------------------------------------------------------------
| 3. ROUTE AUTH & LAINNYA (Harus di luar grup asesor)
|--------------------------------------------------------------------------
*/

// Rute Auth (Login, Logout, Register)
require __DIR__ . '/auth.php';

Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');

Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');

/*
| API & Keep Alive
*/
Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});
Route::get('/api/search-countries', [CountryController::class, 'search'])
    ->name('api.countries.search');