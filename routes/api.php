<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormulirPendaftaranAPI\TandaTanganController;

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