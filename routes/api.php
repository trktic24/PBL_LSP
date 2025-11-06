<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controller API
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\SkemaController;
use App\Http\Controllers\Api\AsesorController;
use App\Http\Controllers\Api\TukController;

Route::prefix('v1')->group(function () {

    // --- RUTE PUBLIK ---
    Route::post('/login', [LoginController::class, 'login']);

    // --- RUTE TERPROTEKSI ---
    Route::middleware('auth:sanctum')->group(function () {
        
        Route::post('/logout', [LoginController::class, 'logout']);
        Route::get('/me', [LoginController::class, 'me']); 

        // RUTE MASTER
        Route::get('/skema', [SkemaController::class, 'index']);
        Route::post('/skema', [SkemaController::class, 'store']);
        Route::get('/asesor', [AsesorController::class, 'index']);
        Route::get('/tuk', [TukController::class, 'index']);

    });
});