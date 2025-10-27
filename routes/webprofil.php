<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\TukController; // <-- BARIS INI DIHAPUS

/*
|--------------------------------------------------------------------------
| Custom Routes (Info TUK dan Alur)
|--------------------------------------------------------------------------
*/
// Rute untuk Detail Skema (Nama opsional kalo nggak ada link langsung)
Route::get('/detail_skema', function () {
    return view('detail_skema');
})->name('skema.detail'); // Contoh nama

// Rute untuk alur sertifikasi
Route::get('/alur-sertifikasi', function () {
    return view('page_info.alur-sertifikasi');
})->name('info.alur'); // Nama untuk dropdown Info

// Rute untuk info TUK (Daftar) - DIKEMBALIKAN KE CLOSURE STATIS
Route::get('/info-tuk', function () {
    return view('page_tuk.info-tuk');
})->name('info.tuk'); 

// Rute untuk detail TUK (Nama opsional) - DIKEMBALIKAN KE CLOSURE STATIS
// Catatan: Parameter {slug} dihapus agar tidak terjadi error di closure
Route::get('/info-tuk/detail/{slug}', function () {
    // Karena tidak ada Controller/Model, View ini akan gagal menampilkan data dinamis.
    // Sementara, kita kembalikan ke view statis saja.
    return view('page_tuk.detail-tuk');
})->name('info.tuk.detail');


/*
|--------------------------------------------------------------------------
| Web Profil Routes
|--------------------------------------------------------------------------
*/
// Rute untuk Visi & Misi
Route::get('/visimisi', function () {
    return view('page_profil.visimisi');
})->name('profil.visimisi'); // Nama untuk dropdown Profil

// Rute untuk Struktur
Route::get('/struktur', function () {
    return view('page_profil.struktur');
})->name('profil.struktur'); // Nama untuk dropdown Profil

// Rute untuk Mitra
Route::get('/mitra', function () {
    return view('page_profil.mitra');
})->name('profil.mitra'); // Nama untuk dropdown Profil

/*
|--------------------------------------------------------------------------
| Halaman Utama & Menu Utama
|--------------------------------------------------------------------------
*/
// Rute halaman default (/)
Route::get('/', function () {
    return view('home');
})->name('home'); // Nama untuk menu Home

// Rute untuk jadwal
Route::get('/jadwal', function () {
    return view('landing_page.jadwal');
})->name('jadwal'); // Nama untuk menu Jadwal Asesmen

// Rute untuk Sertifikasi (buat placeholder dulu)
Route::get('/sertifikasi', function () {
    // return view('landing_page.sertifikasi'); // Ganti ke view yang bener nanti
    return "Halaman Sertifikasi"; // Placeholder
})->name('sertifikasi'); // Nama untuk menu Sertifikasi

// Rute untuk Daftar Asesor (buat placeholder dulu)
Route::get('/daftar-asesor', function () {
    return view('page_info.daftar-asesor');
})->name('info.daftar-asesor'); // Nama untuk dropdown Info

//
Route::get('/detail_jadwal', function () {
    return view('detail_jadwal');
});

// Jangan lupa include routes/auth.php kalo belum
// require __DIR__.'/auth.php'; // Pastikan route 'login' ada di sini
