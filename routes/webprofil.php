<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Profil Routes
|--------------------------------------------------------------------------
|
| Di sinilah Anda dapat mendaftarkan rute untuk bagian profil.
|
*/

// Rute untuk Visi & Misi
// 'page_profil.visimisi' menunjuk ke folder:
// /resources/views/page_profil/visimisi.blade.php
Route::get('/visimisi', function () {
    return view('page_profil.visimisi');
});

// Rute untuk Struktur
Route::get('/struktur', function () {
    return view('page_profil.struktur');
});

// Rute untuk Mitra
Route::get('/mitra', function () {
    return view('page_profil.mitra');
});

// Anda bisa jadikan /visimisi sebagai halaman default jika mau
Route::get('/', function () {
    return view('page_profil.visimisi');
});

// Rute untuk info tuk
Route::get('/info-tuk', function () {
    return view('info_tuk.info-tuk');
});
