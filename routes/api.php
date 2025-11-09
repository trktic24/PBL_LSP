<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormulirPendaftaranAPI\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaranAPI\DataSertifikasiAsesiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ... (Route Sanctum) ...

// !!! TAMBAHKAN 2 ROUTE INI !!!

// API buat ngambil data (GET /api/get-asesi-data/1)
Route::get('/get-asesi-data/{id}', [TandaTanganController::class, 'getAsesiDataApi'])
       ->name('api.get.asesi'); // <-- Namanya kita samain

// API buat nyimpen data (POST /api/ajax-simpan-tandatangan)
Route::post('/ajax-simpan-tandatangan', [TandaTanganController::class, 'storeAjax'])
       ->name('simpan.tandatangan.ajax'); // <-- Namanya kita samain

// API buat hapus Tanda Tangan
Route::post('/ajax-hapus-tandatangan', [TandaTanganController::class, 'deleteAjax'])
       ->name('hapus.tandatangan.ajax');


// buat api data sertifikasi
Route::prefix('data-sertifikasi')->group(function () {
    // GET /api/data-sertifikasi/{id}
    Route::get('/{id}', [DataSertifikasiAsesiController::class, 'getDataSertifikasiAsesiApi'])
        ->name('api.data_sertifikasi.get');

    // POST /api/data-sertifikasi (simpan data baru)
    Route::post('/', [DataSertifikasiAsesiController::class, 'storeAjax'])
        ->name('api.data_sertifikasi.store');

    // DELETE /api/data-sertifikasi/{id} (hapus data)
    Route::delete('/{id}', [DataSertifikasiAsesiController::class, 'deleteAjax'])
        ->name('api.data_sertifikasi.delete');
});
