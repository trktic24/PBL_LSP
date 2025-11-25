<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FrAk07ApiController;

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

// IA_01 API Routes
use App\Http\Controllers\Api\Ia01ApiController;

Route::get('/ia-01/{id}', [Ia01ApiController::class, 'show']);
Route::post('/ia-01/{id}', [Ia01ApiController::class, 'store']);
