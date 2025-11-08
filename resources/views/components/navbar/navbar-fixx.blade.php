<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Asesor\IA02Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\JadwalController; // Import ini
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;

/*
|--------------------------------------------------------------------------
| ROUTE HALAMAN PUBLIK (LANDING PAGE)
|--------------------------------------------------------------------------
*/
// Halaman utama (landing page)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman detail skema (dinamis)
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('detail_skema');

// Halaman daftar jadwal (dinamis)
// Memanggil HomeController (method showJadwalList) untuk mengambil data
Route::get('/jadwal', [HomeController::class, 'showJadwalList'])->name('jadwal');

// Halaman detail jadwal (dinamis)
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail');

// ... (Route publik lainnya seperti /info-tuk, /visimisi, dll.) ...
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');
Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');
Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');
Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');
Route::get('/sertifikasi', function () {
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi');
Route::get('/daftar-asesor', function () {
    return view('landing_page.page_info.daftar-asesor');
})->name('info.daftar-asesor');


/*
|--------------------------------------------------------------------------
| ROUTE INTERNAL ASESOR (SETELAH LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    // Dashboard internal asesor
    Route::get('/home', function () {
        // Nanti ini harus diubah ke Controller untuk ambil data ringkasan
        return view('frontend/home');
    })->name('asesor.home'); // Nama unik

    // Halaman jadwal internal asesor
    // DIPERBAIKI: Menggunakan JadwalController@index untuk mengirim data $jadwals
    Route::get('/asesor/jadwal', [JadwalController::class, 'index'])
         ->name('asesor.jadwal'); // Nama unik

    Route::get('/laporan', function () {
        return view('frontend/laporan');
    })->name('laporan');

    Route::get('/profil', function () {
        return view('frontend/profil');
    })->name('profil');

    // DIPERBAIKI: Route 'daftar_asesi' sekarang menerima ID
    Route::get('/daftar_asesi/{id}', function ($id) {
        // TODO: Ambil data asesi berdasarkan $id jadwal
        return view('frontend/daftar_asesi', ['jadwal_id' => $id]);
    })->name('daftar_asesi');

    Route::get('/tracker', function () {
        return view('frontend/tracker');
    })->name('tracker');

    /* --- Route Formulir --- */
    // DIHAPUS: Duplikasi fr-ia-10
    Route::get('/fr-ia-10', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');

    // DIHAPUS: Route /profile dan /dashboard (sudah ada di auth.php)
});

// DIHAPUS: Route::resource('jadwal', ...) karena ini sumber konflik utama.

Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});

require __DIR__.'/auth.php';