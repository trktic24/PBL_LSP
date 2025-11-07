<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController;


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

// Forms Frontend Routes
Route::get('/FR-IA-10', function () {
    return view('frontend/FR_IA_10');
})->name('FR-IA-10');

Route::get('/fr-ia-06-c', function () {
    return view('frontend/fr_IA_06_c');
})->name('fr_IA_06_c');
Route::get('/fr-ia-06-a', function () {
    return view('frontend/fr_IA_06_a');
})->name('fr_IA_06_a');
Route::get('/fr-ia-06-b', function () {
    return view('frontend/fr_IA_06_b');
})->name('fr_IA_06_b');

Route::get('/fr-ia-07', function () {
    return view('frontend/FR_IA_07');
})->name('FR_IA_07');

Route::get('/fr-ia-05-a', function () {
    return view('frontend/FR_IA_05_A');
})->name('FR_IA_05_A');
Route::get('/fr-ia-05-b', function () {
    return view('frontend/FR_IA_05_B');
})->name('FR_IA_05_B');
Route::get('/fr-ia-05-c', function () {
    return view('frontend/FR_IA_05_C');
})->name('FR_IA_05_C');

Route::get('/fr-ia-02', function () {
    return view('frontend/FR_IA_02');
})->name('FR_IA_02');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])

    ->middleware('auth')
    ->middleware('auth') //
    ->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])
    ->middleware('auth')
    ->name('fr-ia-10.store');
    Route::get('/dashboard', function () {return view('dashboard'); })->middleware(['auth'])->name('dashboard');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])
         ->name('fr-ia-02.show');

    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])
         ->name('fr-ia-02.store');
});

Route::resource('jadwal', JadwalController::class);
// --------------------
// Halaman Home & Detail Skema
// --------------------

Route::get('/', [HomeController::class, 'index'])->name('home');
// Halaman detail skema (klik dari Home)
// Halaman utama (menampilkan semua skema)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// DITAMBAHKAN: Route untuk menangani detail jadwal
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');

Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');

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


// DITAMBAHKAN: Route untuk menangani detail jadwal
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');

Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema');
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');

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


Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});


require __DIR__.'/auth.php';
