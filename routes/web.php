<?php

use App\Models\Asesi;

// ====================================================
// KUMPULAN SEMUA CONTROLLER & MODEL
// ====================================================
use App\Models\Skema;
use App\Models\Asesor;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TukController;

use App\Http\Controllers\Ak03Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SkemaController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\AsesmenController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackerController;
use App\Http\Controllers\Apl01PdfController;
use App\Http\Controllers\SkemaWebController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Apl02\PraasesmenController;
use App\Http\Controllers\Asesor\AsesorTableController;
use App\Http\Controllers\asesmen\AsesmenEsaiController;
use App\Http\Controllers\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\FormulirPendaftaran\TandaTanganController;
use App\Http\Controllers\FormulirPendaftaran\BuktiKelengkapanController;
use App\Models\DataSertifikasiAsesi; // <-- [PENTING] Saya tambahkan ini
use App\Http\Controllers\FormulirPendaftaran\DataSertifikasiAsesiController;
use App\Http\Controllers\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\Ak04API\APIBandingController;
use App\Http\Controllers\IA03Controller;
use App\Http\Controllers\asesmen\AssessmenFRIA04tController;
use App\Http\Controllers\Ia07Controller;
use App\Http\Controllers\IA11\IA11Controller;
use App\Http\Controllers\IA11\SpesifikasiIA11Controller;
use App\Http\Controllers\IA11\PerformaIA11Controller;
use App\Http\Controllers\Ia02Controller;
use App\Http\Controllers\PortofolioController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ====================================================
// GRUP 1: LANDING PAGE & HALAMAN INFO UTAMA
// ====================================================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute Halaman Utama
Route::get('/jadwal', [JadwalController::class, 'index'])->name('jadwal');
Route::get('/daftar-asesor', [AsesorTableController::class, 'index'])->name('info.daftar-asesor');
Route::get('/sertifikasi', function () {
    return 'Halaman Sertifikasi'; // Placeholder
})->name('sertifikasi');

// Rute Detail Skema & Jadwal
Route::get('/skema/{id}', [HomeController::class, 'show'])->name('skema.detail');
Route::get('/detail_skema/{id}', [HomeController::class, 'show'])->name('detail_skema'); // Alias
Route::get('/detail_jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('detail_jadwal');
Route::get('/jadwal/{id}', [HomeController::class, 'showJadwalDetail'])->name('jadwal.detail'); // Alias

// Rute Halaman Info Profil & TUK
Route::get('/alur-sertifikasi', function () {
    return view('landing_page.page_info.alur-sertifikasi');
})->name('info.alur');
Route::get('/info-tuk', [TukController::class, 'index'])->name('info.tuk');
Route::get('/detail-tuk/{id}', [TukController::class, 'showDetail'])->name('info.tuk.detail');

Route::get('/visimisi', function () {
    return view('landing_page.page_profil.visimisi');
})->name('profil.visimisi');
Route::get('/struktur', function () {
    return view('landing_page.page_profil.struktur');
})->name('profil.struktur');
Route::get('/mitra', function () {
    return view('landing_page.page_profil.mitra');
})->name('profil.mitra');

// ====================================================
// GRUP 2: AUTENTIKASI & DASHBOARD
// ====================================================

Route::get('/dashboard', function () {
    return view('dashboard');
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Route untuk file auth.php
require __DIR__ . '/auth.php';

// ====================================================
// GRUP 3: TRACKER ASESI (WAJIB LOGIN)
// ====================================================

Route::get('/tracker/{jadwal_id?}', [TrackerController::class, 'index'])
    ->middleware('auth')
    ->name('tracker');

// API (AJAX) untuk mendaftar ke jadwal
Route::post('/api/jadwal/daftar', [TrackerController::class, 'daftarJadwal'])
    ->middleware('auth')
    ->name('api.jadwal.daftar');

// ====================================================
// GRUP 4: FORMULIR PENDAFTARAN (APL-01, APL-02, DLL)
// ====================================================

// --- Formulir APL-01: Data Sertifikasi ---
// INI ADALAH ROUTE YANG BENAR (YANG LAMA SUDAH SAYA HAPUS)
Route::get('/data_sertifikasi/{id_sertifikasi}', function ($id_sertifikasi) {
    try {
        // Kita cari data pendaftarannya (sertifikasi), BUKAN asesinya
        $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

        // Kirim ID pendaftaran dan data asesi (untuk sidebar) ke Blade
        return view('formulir_pendaftaran/data_sertifikasi', [
            'id_sertifikasi_untuk_js' => $sertifikasi->id_data_sertifikasi_asesi,
            'asesi' => $sertifikasi->asesi,
            'sertifikasi' => $sertifikasi,
        ]);
    } catch (\Exception $e) {
        // Kalo gak ketemu, balikin ke tracker
        return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
    }
})->name('data.sertifikasi');

Route::get('/bukti_pemohon/{id_sertifikasi}', function ($id_sertifikasi) {
    try {
        // [PERBAIKAN] Ambil data berdasarkan ID Sertifikasi (Pendaftaran), bukan ID Asesi
        // Kita juga load 'asesi'-nya biar sidebar tetep jalan
        $sertifikasi = DataSertifikasiAsesi::with('asesi')->findOrFail($id_sertifikasi);

        return view('formulir_pendaftaran/bukti_pemohon', [
            'sertifikasi' => $sertifikasi, // Kirim data pendaftaran lengkap
            'asesi' => $sertifikasi->asesi, // Kirim data orangnya buat sidebar
        ]);
    } catch (\Exception $e) {
        return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
    }
})->name('bukti.pemohon');

Route::get('/halaman-tanda-tangan/{id_sertifikasi}', function ($id_sertifikasi) {
    try {
        // 1. Cari Data Pendaftaran berdasarkan ID Pendaftaran (BUKAN ID Asesi)
        $sertifikasi = DataSertifikasiAsesi::with('asesi.dataPekerjaan')->findOrFail($id_sertifikasi);

        return view('formulir_pendaftaran/tanda_tangan_pemohon', [
            'sertifikasi' => $sertifikasi,
            'asesi' => $sertifikasi->asesi,
        ]);
    } catch (\Exception $e) {
        // 2. INI YANG BIKIN KAMU MENTAL.
        // Kalau ID Sertifikasi salah kirim (misal kirim ID Asesi padahal butuh ID Sertifikasi),
        // findOrFail akan gagal dan masuk ke sini.
        return redirect('/tracker')->with('error', 'Data Pendaftaran tidak ditemukan.');
    }
})->name('show.tandatangan');

// --- Halaman Statis Formulir ---
Route::get('/tunggu_upload_dokumen', function () {
    return view('formulir_pendaftaran/tunggu_upload_dokumen');
});
Route::get('/belum_memenuhi', function () {
    return view('formulir_pendaftaran/dokumen_belum_memenuhi');
});
Route::get('/formulir-selesai', function () {
    return 'BERHASIL DISIMPAN! Ini halaman selanjutnya.';
})->name('form.selesai');

Route::get('/pra-asesmen/{id_sertifikasi}', [PraasesmenController::class, 'index'])
    ->middleware('auth')
    ->name('apl02.view');

// --- Asesmen Lainnya Views ---
// --- PDF Download ---
Route::get('/cetak/apl01/{id_data_sertifikasi}', [Apl01PdfController::class, 'generateApl01'])->name('pdf.apl01');

//umpan balik (ak03)
Route::get('/umpan-balik', [Ak03Controller::class, 'index'])->name('ak03.index');
Route::post('/umpan-balik/store', [Ak03Controller::class, 'store'])->name('ak03.store');

// Route untuk menampilkan form FR.IA.04A (GET) - ASESOR
// TIDAK ADA PARAMETER ID
Route::get('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'showIA04A'])->name('fria04a.show');

// Route untuk menangani submit form FR.IA.04A (POST) - ASESOR
Route::post('/FRIA04_Asesor', [AssessmenFRIA04tController::class, 'storeIA04A'])->name('fria04a.store');


// --- ASESI VIEW & SUBMIT ---
// Route baru untuk menampilkan form (GET) - ASESI (READ-ONLY + INPUT TANGGAPAN)
// TIDAK ADA PARAMETER ID
Route::get('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'showIA04AAsesi'])->name('fria04a.asesi.show');

// Route baru untuk menyimpan input ASESI (Hanya Tanggapan)
Route::post('/FRIA04_Asesi', [AssessmenFRIA04tController::class, 'storeIA04AAsesi'])->name('fria04a.asesi.store');

// Route untuk menampilkan Form Portofolio (Halaman Awal)
Route::get('/portofolio', [PortofolioController::class, 'index'])
    ->name('portofolio.index');

// Route untuk memproses/menyimpan file yang diunggah Asesi
Route::post('/portofolio/store', [PortofolioController::class, 'store'])
    ->name('portofolio.store');

// Route untuk menghapus/membatalkan dokumen (jika Anda ingin mengimplementasikan fungsi hapus)
Route::delete('/portofolio/delete', [PortofolioController::class, 'destroyBukti'])
    ->name('portofolio.destroy');


// ====================================================
// GRUP 6: ROUTE LAIN-LAIN (API & KEEP ALIVE)
// ====================================================

Route::get('/keep-alive', function () {
    return response()->json(['status' => 'session_refreshed']);
});
Route::get('/api/search-countries', [CountryController::class, 'search'])->name('api.countries.search');

// 1. Route Klik Bayar
Route::get('/bayar/{id_sertifikasi}', [PaymentController::class, 'createTransaction'])
    ->middleware('auth')
    ->name('payment.create');

// 2. Route Sukses/Finish (Callback Midtrans)
// [PERBAIKAN] Nama route disesuaikan dengan config Midtrans di controller
Route::get('/pembayaran_diproses', [PaymentController::class, 'processed'])->name('pembayaran_diproses');

// 3. Route Batal/Cancel
Route::get('/pembayaran_batal', [PaymentController::class, 'paymentCancel'])->name('payment.cancel');

Route::get('/kerahasiaan/fr-ak01/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'show'])->name('kerahasiaan.fr_ak01');

Route::get('/jadwal-tuk/{id_sertifikasi}', [JadwalTukAPIController::class, 'show'])->name('show.jadwal_tuk');

// Route Web AK.04 (DISESUAIKAN)
Route::get('/banding/fr-ak04/{id_sertifikasi}', [APIBandingController::class, 'show'])->name('banding.fr_ak04'); // KRITIS: Menggunakan fr-ak04

// Route Placeholder untuk Umpan Balik (AK.03)
Route::get('/asesi/umpan-balik/{id_sertifikasi}', function ($id_sertifikasi) {
    // Arahkan kembali ke halaman banding (saat ini) atau halaman data utama
    return redirect()->route('banding.show', ['id_sertifikasi' => $id_sertifikasi]);
})->name('umpan.balik');
Route::get('/jadwal-tuk/{id_sertifikasi}', [JadwalTukAPIController::class, 'show'])->name('show.jadwal_tuk');

Route::get('/asesmen/ia05/{id_sertifikasi}', [AsesmenPilihanGandaController::class, 'indexPilihanGanda'])->name('asesmen.ia05.view');

Route::get('/asesmen/ia06/{id_sertifikasi}', [AsesmenEsaiController::class, 'indexEsai'])->name('asesmen.ia06.view');

Route::get('/payment/{id_sertifikasi}/invoice', [PaymentController::class, 'downloadInvoice'])->name('payment.invoice');

Route::middleware(['auth'])->group(function () {
    // --- IA.01 sementara (biar tidak error) ---
    Route::get('/ia01/{id_sertifikasi}', function ($id_sertifikasi) {
        return "HALAMAN IA01 BELUM DIBUAT — ID: " . $id_sertifikasi;
    })->name('ia01.index');

    // --- ROUTE FR.IA.02 (TUGAS PRAKTIK / DEMONSTRASI) ---

    // 1. Menampilkan halaman IA02 (READ-ONLY)
    // id_sertifikasi = ID Data Sertifikasi Asesi
    Route::get('/ia02/{id_sertifikasi}', [Ia02Controller::class, 'index'])
        ->name('ia02.index');

   
    // 2. Tombol "Selanjutnya" → redirect ke IA03
    Route::post('/ia02/{id_sertifikasi}/next', [Ia02Controller::class, 'next'])
        ->name('ia02.next');
});

    Route::middleware(['auth'])->group(function () {

    // Halaman utama IA03 (list pertanyaan + identitas lengkap)
    Route::get('/ia03/{id_data_sertifikasi_asesi}', [IA03Controller::class, 'index'])->name('ia03.index');

    // Halaman detail satu pertanyaan (opsional)
    Route::get('/ia03/detail/{id}', [IA03Controller::class, 'show'])->name('ia03.show');
});

Route::middleware(['auth'])->group(function () {
    // --- ROUTE FR.IA.07 (PERTANYAAN LISAN) ---

    // 1. Route untuk Menampilkan Form (GET)
    // Parameter {id_sertifikasi} diperlukan agar Controller tahu data siapa yang ditampilkan
    Route::get('/asesi/ia07/{id_sertifikasi}', [Ia07Controller::class, 'index'])->name('ia07.index');

    // 2. Route untuk Menyimpan Jawaban (POST)
    // Nama route 'ia07.store' harus sama persis dengan action di form blade
    Route::post('/asesi/ia07/store', [Ia07Controller::class, 'store'])->name('ia07.store');

    // --- ROUTE FR.IA.11 (CEKLIS REVIU PRODUK) ---
    // 1. Route untuk Menampilkan Data (READ)
    Route::get('/ia11/{id_data_sertifikasi_asesi}', [IA11Controller::class, 'show'])->name('ia11.index');

    // 2. Route untuk Menyimpan Data Baru (POST)
    Route::post('/ia11/store', [IA11Controller::class, 'store'])->name('ia11.store');

    // 3. Route untuk Memperbarui Data (PUT/PATCH)
    // Menggunakan ID primary key dari tabel IA11, bukan ID sertifikasi
    Route::put('/ia11/{id}', [IA11Controller::class, 'update'])->name('ia11.update');

    // 4. Route untuk Menghapus Data (DELETE)
    Route::delete('/ia11/{id}', [IA11Controller::class, 'destroy'])->name('ia11.destroy');
});

// ====================================================
// ROUTE ADMIN (MASTER DATA IA.11)
// ====================================================

// Asumsi route ini berada di belakang middleware Admin
Route::prefix('admin/master/ia11')
    ->name('admin.master.ia11.')
    ->group(function () {
        // --- Spesifikasi Produk Master ---
        Route::resource('spesifikasi', SpesifikasiIA11Controller::class)->except(['create', 'edit']) ->middleware('user.type:admin');

        // --- Performa Produk Master ---
        Route::resource('performa', PerformaIA11Controller::class)->except(['create', 'edit'])->middleware('user.type:admin');
    });
