<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Custom Routes (Info TUK dan Alur) - DITEMPATKAN PALING ATAS
|--------------------------------------------------------------------------
|
| Prioritaskan rute spesifik Anda di sini.
|
*/
// Rute untuk Detail Skema
Route::get('/detail_skema', function () {
    return view('detail_skema');
});

// Rute untuk alur sertifikasi
Route::get('/alur-sertifikasi', function () {
    return view('page_info.alur-sertifikasi');
});

// Rute untuk info TUK (Daftar)
Route::get('/info-tuk', function () {
    return view('page_tuk.info-tuk');
});

// Rute untuk detail TUK
Route::get('/detail-tuk', function () {
    return view('page_tuk.detail-tuk');
});

/*
|--------------------------------------------------------------------------
| Web Profil Routes - Ditempatkan di tengah
|--------------------------------------------------------------------------
*/

// Rute untuk Visi & Misi
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

/*
|--------------------------------------------------------------------------
| Halaman Utama (Default) - DITEMPATKAN PALING BAWAH
|--------------------------------------------------------------------------
*/

// Rute halaman default (/)
Route::get('/', function () {
    return view('home');
});