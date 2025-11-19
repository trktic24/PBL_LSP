<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Test;

// --- KUMPULAN SEMUA CONTROLLER (JADI SATU DI ATAS) ---
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TukController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\AsesorTableApiController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\DataSertifikasiAsesiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- GRUP 1: RUTE AUTENTIKASI (LOGIN, REGISTER, GOOGLE) ---
Route::post('/login', [LoginController::class, 'login']);

Route::prefix('register')->group(function () {
    Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
    Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
});

Route::prefix('auth/google')->group(function () {
    Route::get('redirect', [GoogleApiController::class, 'redirect']);
    Route::get('callback', [GoogleApiController::class, 'callback']);
});


// --- GRUP 2: RUTE YANG BUTUH LOGIN (SANCTUM) ---
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'User data retrieved successfully',
        'data' => $request->user()
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);


// --- GRUP 3: RUTE DATA PUBLIK (READ-ONLY) ---
Route::get('/skema', [SkemaController::class, 'index']);
Route::get('/asesor', [AsesorTableApiController::class, 'index']);


// --- GRUP 4: RUTE RESOURCE (CRUD LENGKAP) ---
// Rute API TUK (Tempat Uji Kompetensi)
Route::apiResource('tuks', TukController::class);


// --- GRUP 5: RUTE FORMULIR ASESI (APL/AK) ---

// Data Sertifikasi
Route::prefix('data-sertifikasi')->group(function () {
    Route::get('/detail/{id}', [DataSertifikasiAsesiController::class, 'getDetailSertifikasiApi'])
        ->name('api.sertifikasi.detail');
    Route::get('/{id}', [DataSertifikasiAsesiController::class, 'getDataSertifikasiAsesiApi'])
        ->name('api.data_sertifikasi.get');
    Route::post('/', [DataSertifikasiAsesiController::class, 'storeAjax'])
        ->name('api.data_sertifikasi.store');
    Route::get('/get-sertifikasi-detail/{id}', [DataSertifikasiAsesiController::class, 'getDetailSertifikasiApi'])
        ->name('api.sertifikasi.detail');
});

Route::prefix('bukti-kelengkapan')->group(function () {
    
    // 1. GET Data: Mengambil daftar bukti berdasarkan ID Data Sertifikasi
    // URL: /api/bukti-kelengkapan/list/{id_data_sertifikasi_asesi}
    Route::get('/list/{id_data_sertifikasi_asesi}', [BuktiKelengkapanController::class, 'getDataBuktiKelengkapanApi'])
        ->name('api.bukti_kelengkapan.get');

    // 2. POST Data: Simpan atau Update bukti (Upload File)
    // URL: /api/bukti-kelengkapan/store
    Route::post('/store', [BuktiKelengkapanController::class, 'storeAjax'])
        ->name('api.bukti_kelengkapan.store');

    // 3. DELETE Data: Hapus bukti berdasarkan ID Bukti Dasar
    // URL: /api/bukti-kelengkapan/delete/{id}
    Route::delete('/delete/{id}', [BuktiKelengkapanController::class, 'deleteAjax'])
        ->name('api.bukti_kelengkapan.delete');
});

// Kerahasiaan (AK01)
Route::get('/get-frak01-data/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])
        ->name('api.get.frak01');
Route::post('/setuju-kerahasiaan/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])
        ->name('api.setuju.frak01');

// Tanda Tangan Asesi
Route::get('/show-all',[TandaTanganAPIController::class, 'index']);
Route::get('/show-detail/{id_asesi}',[TandaTanganAPIController::class, 'show']);

// inih
Route::apiResource('test', Test::class);