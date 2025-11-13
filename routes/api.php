<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormulirPendaftaranAPI\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaranAPI\DataSertifikasiAsesiController;
use App\Http\Controllers\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\FormulirPendaftaranAPI\BuktiKelengkapanController;


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

// API buat nyimpen data (POST /api/ajax-simpan-tandatangan/1)
Route::post('/ajax-simpan-tandatangan/{id_asesi}', [TandaTanganController::class, 'storeAjax'])
       ->name('simpan.tandatangan.ajax');

// API buat nge-hapus data (POST /api/ajax-hapus-tandatangan/1)
Route::post('/ajax-hapus-tandatangan/{id_asesi}', [TandaTanganController::class, 'deleteAjax'])
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

//bukti kelengkapan api

//api bukti kelengkapan
Route::prefix('bukti-kelengkapan')->group(function () {
    // GET /api/bukti-kelengkapan/{id_data_sertifikasi_asesi}
    Route::get('/{id_data_sertifikasi_asesi}', [BuktiKelengkapanController::class, 'getDataBuktiKelengkapanApi'])
        ->name('api.bukti_kelengkapan.get');

    // POST /api/bukti-kelengkapan (simpan atau update data)
    Route::post('/', [BuktiKelengkapanController::class, 'storeAjax'])
        ->name('api.bukti_kelengkapan.store');

    // DELETE /api/bukti-kelengkapan/{id} (hapus data)
    Route::delete('/{id}', [BuktiKelengkapanController::class, 'deleteAjax'])
        ->name('api.bukti_kelengkapan.delete');
});



// API KERAHASIAAN AK01
Route::get('/get-frak01-data/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'getFrAk01Data'])
       ->name('api.get.frak01');

Route::post('/setuju-kerahasiaan/{id_asesi}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])
       ->name('api.setuju.frak01');