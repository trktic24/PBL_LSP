<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TukController;
use App\Http\Controllers\Asesor\AsesorTableController;

/*
|--------------------------------------------------------------------------
| Halaman Home & Detail Skema
|--------------------------------------------------------------------------
| Diperbaiki: hanya satu route /skema/{id} yang aktif untuk mencegah konflik.
| Route 'skema.detail' dijaga agar sesuai dengan yang dipanggil di Blade.
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
Route::get('/jadwal', function () {
    return view('landing_page.jadwal');
})->name('jadwal');

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


require __DIR__.'/auth.php';

// // Grup rute yang WAJIB LOGIN (sudah ada 'auth' dari Breeze)
// Route::middleware(['auth'])->group(function () {

//     // Contoh rute dashboard (semua role yg login bisa akses)
//     Route::get('/dashboard', [DashboardController::class, 'index'])
//         ->name('dashboard');

//     // --- INI CONTOH ROLE HIERARKI ---

//     // 1. HANYA Superadmin
//     // Admin, Asesor, Asesi -> GABISA MASUK (403 Forbidden)
//     Route::middleware(['role:superadmin'])->group(function () {
//         Route::get('/superadmin/logs', [SuperAdminController::class, 'viewLogs']);
//         // ...rute superadmin lainnya
//     });

//     // 2. HANYA Admin dan Superadmin
//     // Asesor, Asesi -> GABISA MASUK
//     Route::middleware(['role:admin,superadmin'])->group(function () {
//         Route::get('/admin/manage-users', [AdminController::class, 'manageUsers']);
//         // ...rute admin lainnya
//     });

//     // 3. HANYA Asesor (dan Admin/Superadmin jika perlu)
//     // Asesi -> GABISA MASUK
//     Route::middleware(['role:asesor,admin,superadmin'])->group(function () {
//         Route::get('/asesor/data-asesi', [AsesorController::class, 'viewAsesi']);
//         // ...rute asesor lainnya
//     });

//     // 4. HANYA Asesi
//     // Semua role lain -> GABISA MASUK
//     Route::middleware(['role:asesi'])->group(function () {
//         Route::get('/asesi/profile-saya', [AsesiController::class, 'myProfile']);
//         // ...rute asesi lainnya
//     });

// });
