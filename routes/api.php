<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\GoogleApiController;
use App\Http\Controllers\Api\Auth\SkemaController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => 'User data retrieved successfully',
        'data' => $request->user()
    ]);
});

Route::post('/login', [LoginController::class, 'login']);
Route::prefix('register')->group(function () {
    Route::post('/asesi', [RegisterController::class, 'registerAsesi']);
    Route::post('/asesor', [RegisterController::class, 'registerAsesor']);
});
Route::middleware('auth:sanctum')->post('/logout', [LogoutController::class, 'logout']);
Route::prefix('auth/google')->group(function () {
    Route::get('redirect', [GoogleApiController::class, 'redirect']);
    Route::get('callback', [GoogleApiController::class, 'callback']);
});


Route::get('/skema', [SkemaController::class, 'index']);
