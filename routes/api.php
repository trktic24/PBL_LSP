<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrAk07ApiController;
use App\Http\Controllers\Api\Mapa02ApiController;
use App\Http\Controllers\Api\Ia02ApiController;
use App\Http\Controllers\Api\IA09Controller;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// FR_AK_07 API Routes
Route::get('/fr-ak-07/{id}', [FrAk07ApiController::class, 'show']);
Route::post('/fr-ak-07/{id}', [FrAk07ApiController::class, 'store']);

// FR_MAPA_02 API Routes
Route::get('/mapa-02/{id}', [Mapa02ApiController::class, 'show']);
Route::post('/mapa-02/{id}', [Mapa02ApiController::class, 'store']);

// FR_IA_02 API Routes
Route::get('/ia-02/{id}', [Ia02ApiController::class, 'show']);
Route::post('/ia-02/{id}', [Ia02ApiController::class, 'store']);

// IA_01 API Routes
use App\Http\Controllers\Api\Ia01ApiController;

Route::get('/ia-01/{id}', [Ia01ApiController::class, 'show']);
Route::post('/ia-01/{id}', [Ia01ApiController::class, 'store']);

// API untuk mendapatkan data IA09
Route::get('/ia09/{id_data_sertifikasi_asesi}', [IA09Controller::class, 'getIA09Data']);
// API untuk menyimpan data IA09 (opsional)
Route::post('/ia09/store', [IA09Controller::class, 'storeWawancara']);