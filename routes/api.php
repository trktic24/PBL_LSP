<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\JadwalController as ApiJadwalController;

// Definisikan route API untuk mengambil data jadwal
Route::get('/jadwal-asesmen', [ApiJadwalController::class, 'index']);