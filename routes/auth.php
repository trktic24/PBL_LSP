<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// ======================================================
// 1. CONTROLLERS UTAMA & AUTH
// ======================================================
use App\Http\Controllers\Ak02Controller;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\IA06Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\IA11Controller;
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\FrMapa01Controller;

// ======================================================
// 2. CONTROLLERS ADMIN
// ======================================================
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\MitraController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\Admin\AsesorController;
use App\Http\Controllers\Admin\BeritaController;
use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Asesi\IA03\IA03Controller;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\DaftarHadirController;
use App\Http\Controllers\Admin\DetailSkemaController;
use App\Http\Controllers\Asesi\Pdf\Ak01PdfController;

// ======================================================
// 3. CONTROLLERS ASESOR
// ======================================================
use App\Http\Controllers\Admin\AsesiProfileController;
use App\Http\Controllers\Asesi\Pdf\Apl01PdfController;
use App\Http\Controllers\Asesi\Pdf\Apl02PdfController;
use App\Http\Controllers\Admin\AsesorProfileController;

// ======================================================
// 4. CONTROLLERS ASESI
// ======================================================
use App\Http\Controllers\Asesor\AsesiTrackerController;
use App\Http\Controllers\Asesor\AsesorJadwalController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Asesi\IA02\Ia02AsesiController;
use App\Http\Controllers\Asesi\IA07\Ia07AsesiController;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\umpan_balik\Ak03Controller;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\StrukturOrganisasiController;
use App\Http\Controllers\Asesi\Ak04API\APIBandingController;
use App\Http\Controllers\Asesi\pembayaran\PaymentController;
use App\Http\Controllers\Asesi\RiwayatSertifikasiController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Asesi\asesmen\AsesmenEsaiController;
use App\Http\Controllers\Asesi\Pdf\KartuPesertaPdfController;

// PDF Controllers
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Asesi\asesmen\AssessmenFRIA09Controller;
use App\Http\Controllers\Asesi\JadwalTukAPI\JadwalTukAPIController;

// ======================================================
// 5. CONTROLLERS FORM (SHARED/SPECIFIC)
// ======================================================
use App\Http\Controllers\Asesi\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\JadwalController as AdminJadwalController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Asesi\DashboardController as AsesiDashboardController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\Asesi\ProfileController as AsesiSelfProfileController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;

// Namespace Asesi Specific for IA
use App\Http\Controllers\Asesor\ProfileController as AsesorSelfProfileController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\DataSertifikasiAsesiController;

/*
|--------------------------------------------------------------------------
| Authentication & Role-Based Routes
|--------------------------------------------------------------------------
*/

// ======================================================
// A. RUTE GUEST (Belum Login)
// ======================================================
Route::middleware('guest')->group(function () {
    // User Login/Register
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:5,1');

    // Password Reset
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');

    // Admin Login Routes
    Route::prefix('admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login_admin');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login_admin.post');
        Route::get('forgot-password', [AdminLoginController::class, 'showForgotPassword'])->name('forgot_pass');
        Route::post('forgot-password', [AdminLoginController::class, 'sendResetLink'])->name('forgot_pass.send');
    });
});

// Google Auth
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// ======================================================
// B. RUTE AUTHENTICATED (Wajib Login)
// ======================================================
Route::middleware('auth')->group(function () {
    // 1. General User Management
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // 2. Shared Form Views (Formulir Asesmen IA)

    // FR.AK.01
    Route::get('/fr-ak-01/{id}', [PersetujuanKerahasiaanAPIController::class, 'show'])->name('ak01.index');
    Route::post('/fr-ak-01/{id}', [PersetujuanKerahasiaanAPIController::class, 'simpanPersetujuan'])->name('ak01.store');

    // FR.IA.02
    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');

    // FR.IA.05 (Kompleks Multi-Role)
    Route::middleware(['role:admin,asesor,asesi'])
        ->get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])
        ->name('FR_IA_05_A');
    Route::middleware(['role:admin'])
        ->post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])
        ->name('ia-05.store.soal');
    Route::middleware(['role:asesi'])
        ->post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])
        ->name('ia-05.store.jawaban');
    Route::middleware(['role:admin,asesor'])
        ->get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])
        ->name('FR_IA_05_B');
    Route::middleware(['role:admin'])
        ->post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])
        ->name('ia-05.store.kunci');
    Route::middleware(['role:admin,asesor'])
        ->get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])
        ->name('FR_IA_05_C');
    Route::middleware(['role:asesor'])
        ->post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])
        ->name('ia-05.store.penilaian');

    // FR.IA.06 (Views)
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');

    // FR.IA.07
    Route::get('/fr-ia-07', [IA07Controller::class, 'index'])->name('FR_IA_07');

    // FR.IA.10
    Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');

    // APL-01 & MAPA
    Route::get('/mapa01/show/{id}', [FrMapa01Controller::class, 'index'])->name('mapa01.index');
    Route::post('/mapa01/store', [FrMapa01Controller::class, 'store'])->name('mapa01.store');

    Route::get('/mapa02/show/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/store/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');

    // ======================================================
    // 3. AREA ADMIN
    // ======================================================
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
            Route::get('/notifications', fn() => view('admin.notifications.notifications_admin'))->name('notifications');

            // Profile Admin
            Route::get('/profile_admin', fn() => view('admin.profile.profile_admin'))->name('profile_admin');
            Route::controller(AdminProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
            });

            // ----------------- Master Data -----------------

            // Master TUK
            Route::resource('master/tuk', TukAdminController::class)->names([
                'index' => 'master_tuk',
                'create' => 'add_tuk',
                'store' => 'add_tuk.store',
                'edit' => 'edit_tuk',
                'update' => 'update_tuk',
                'destroy' => 'delete_tuk',
            ]);

            // Master Category
            Route::resource('master/category', CategoryController::class)->names([
                'index' => 'master_category',
                'create' => 'add_category',
                'store' => 'add_category.store',
                'edit' => 'edit_category',
                'update' => 'update_category',
                'destroy' => 'delete_category',
            ]);

            // Master Mitra
            Route::resource('master/mitra', MitraController::class)->names([
                'index' => 'master_mitra',
                'create' => 'add_mitra',
                'store' => 'add_mitra.store',
                'edit' => 'edit_mitra',
                'update' => 'update_mitra',
                'destroy' => 'delete_mitra',
            ]);

            // Master Berita
            Route::resource('master/berita', BeritaController::class)->names([
                'index' => 'master_berita',
                'create' => 'add_berita',
                'store' => 'add_berita.store',
                'edit' => 'edit_berita',
                'update' => 'update_berita',
                'destroy' => 'delete_berita',
            ]);

            // Master Asesi
            Route::resource('master/asesi', AsesiController::class)->names([
                'index' => 'master_asesi',
                'create' => 'add_asesi',
                'store' => 'add_asesi.store',
                'edit' => 'edit_asesi',
                'update' => 'update_asesi',
                'destroy' => 'delete_asesi',
            ]);

            // Master Skema
            Route::controller(SkemaController::class)
                ->prefix('master/skema')
                ->group(function () {
                    Route::get('/', 'index')->name('master_skema');
                    Route::post('/add', 'store')->name('add_skema.store');
                    Route::get('/add', 'create')->name('add_skema');
                    Route::get('/edit/{id_skema}', 'edit')->name('edit_skema');
                    Route::put('/update/{id_skema}', 'update')->name('update_skema');
                    Route::delete('/delete/{id_skema}', 'destroy')->name('delete_skema');
                });

            // Detail Skema (Kelompok, Unit)
            Route::controller(DetailSkemaController::class)
                ->prefix('master/skema/detail')
                ->group(function () {
                    Route::get('/{id_skema}', 'index')->name('skema.detail');
                    Route::get('/{id_skema}/add-kelompok', 'createKelompok')->name('skema.detail.add_kelompok');
                    Route::post('/{id_skema}/add-kelompok', 'storeKelompok')->name('skema.detail.store_kelompok');
                    Route::get('/kelompok/{id_kelompok}/edit', 'editKelompok')->name('skema.detail.edit_kelompok');
                    Route::put('/kelompok/{id_kelompok}', 'updateKelompok')->name('skema.detail.update_kelompok');
                    Route::delete('/kelompok/{id_kelompok}', 'destroyKelompok')->name('skema.detail.destroy_kelompok');
                    Route::delete('/unit/{id_unit}', 'destroyUnit')->name('skema.detail.destroy_unit');
                    Route::put('/{id_skema}/update-form', 'updateListForm')->name('skema.detail.update_form');
                });

            // Profile Asesi
            Route::controller(AsesiProfileController::class)
                ->prefix('asesi/{id_asesi}')
                ->group(function () {
                    Route::get('/settings', 'settings')->name('asesi.profile.settings');
                    Route::get('/form', 'form')->name('asesi.profile.form');
                    Route::get('/tracker', 'tracker')->name('asesi.profile.tracker');

                    // --- FITUR BUKTI KELENGKAPAN ---
                    Route::get('/bukti', 'bukti')->name('asesi.profile.bukti'); // Halaman View
                    Route::post('/bukti/store', 'storeBukti')->name('asesi.profile.bukti.store'); // Upload
                    Route::post('/bukti/update/{id_bukti}', 'updateBukti')->name('asesi.profile.bukti.update');
                    Route::delete('/bukti/delete/{id_bukti}', 'deleteBukti')->name('asesi.profile.bukti.delete'); // Hapus

                    Route::post('/ttd/store', 'storeTandaTangan')->name('asesi.profile.ttd.store');
                    Route::delete('/ttd/delete', 'deleteTandaTangan')->name('asesi.profile.ttd.delete');
                });

            // Master Asesor
            Route::controller(AsesorController::class)->group(function () {
                Route::get('/master_asesor', 'index')->name('master_asesor');
                // Steps
                Route::get('/add_asesor1', 'createStep1')->name('add_asesor1');
                Route::post('/add_asesor1', 'postStep1')->name('add_asesor1.post');
                Route::get('/add_asesor2', 'createStep2')->name('add_asesor2');
                Route::post('/add_asesor2', 'postStep2')->name('add_asesor2.post');
                Route::get('/add_asesor3', 'createStep3')->name('add_asesor3');
                Route::post('/asesor/store', 'store')->name('asesor.store');
                // Edit Steps
                Route::get('/edit-asesor-step-1/{id_asesor}', 'editStep1')->name('edit_asesor1');
                Route::patch('/update-asesor-step-1/{id_asesor}', 'updateStep1')->name('asesor.update.step1');
                Route::get('/edit-asesor-step-2/{id_asesor}', 'editStep2')->name('edit_asesor2');
                Route::patch('/update-asesor-step-2/{id_asesor}', 'updateStep2')->name('asesor.update.step2');
                Route::get('/edit-asesor-step-3/{id_asesor}', 'editStep3')->name('edit_asesor3');
                Route::patch('/update-asesor-step-3/{id_asesor}', 'updateStep3')->name('asesor.update.step3');
                Route::delete('/asesor/{id_asesor}', 'destroy')->name('asesor.destroy');
            });

            // Master Struktur Organisasi
            Route::resource('master/struktur', StrukturOrganisasiController::class)->names([
                'index' => 'master_struktur',
                'create' => 'add_struktur',
                'store' => 'add_struktur.store',
                'edit' => 'edit_struktur',
                'update' => 'update_struktur',
                'destroy' => 'delete_struktur',
            ]);

            // ==========================================================
            // DETAIL PROFIL ASESOR (Admin View) - [UPDATED SECTION]
            // ==========================================================
            Route::controller(AsesorProfileController::class)
                ->prefix('asesor/{id_asesor}')
                ->group(function () {
                    Route::get('/profile', 'showProfile')->name('asesor.profile');
                    Route::get('/bukti', 'showBukti')->name('asesor.bukti');
                    Route::get('/tinjauan', 'showTinjauan')->name('asesor_profile_tinjauan');

                    // [FIX] Mengubah nama route agar sesuai dengan View (daftar_asesi)
                    Route::get('/jadwal/{id_jadwal}/asesi', 'showDaftarAsesi')->name('asesor.daftar_asesi');

                    // [FIX] Parameter Tracker dibuat OPSIONAL (?) agar tidak error di Sidebar
                    Route::get('/tracker/{id_data_sertifikasi_asesi?}', 'showTracker')->name('asesor.tracker');

                    // Route untuk Tracker Skema (Timeline per Jadwal/Asesi)
                    Route::get('/tracker_skema/{id_jadwal}', 'showTrackerSkema')->name('asesor.tracker_skema');

                    Route::get('/assessment/{id_data_sertifikasi_asesi}', 'showAssessmentDetail')->name('asesor.assessment.detail');
                    Route::post('/update-status', 'updateStatus')->name('asesor.update_status');
                });

            // Master Jadwal
            Route::resource('master/jadwal', AdminJadwalController::class)->names([
                'index' => 'master_schedule',
                'create' => 'add_schedule',
                'store' => 'add_schedule.store',
                'edit' => 'edit_schedule',
                'update' => 'update_schedule',
                'destroy' => 'delete_schedule',
            ]);
            Route::get('/jadwal_admin', [AdminJadwalController::class, 'showCalendar'])->name('schedule_admin');

            // Daftar Hadir & Berita Acara
            Route::controller(DaftarHadirController::class)
                ->prefix('master/jadwal/{id_jadwal}')
                ->group(function () {
                    Route::get('/daftar_hadir', 'daftarHadir')->name('schedule.attendance');
                    Route::delete('/attendance/delete/{id}', 'destroy')->name('schedule.attendance.destroy');
                    Route::get('/daftar_hadir/pdf', 'exportPdfdaftarhadir')->name('attendance.pdf');
                });

            // Bank Soal IA-06 (View Only for Admin, Full Access for Superadmin)
            Route::get('/bank-soal-ia06', [IA06Controller::class, 'adminIndex'])->name('ia06.index');

            // Superadmin ONLY Actions
            Route::middleware(['role:superadmin'])->group(function () {
                Route::post('/bank-soal-ia06', [IA06Controller::class, 'adminStoreSoal'])->name('ia06.store');
                Route::put('/bank-soal-ia06/{id}', [IA06Controller::class, 'adminUpdateSoal'])->name('ia06.update');
                Route::delete('/bank-soal-ia06/{id}', [IA06Controller::class, 'adminDestroySoal'])->name('ia06.destroy');
            });

            // FR.IA.01 - View Only for Admin (Cek Observasi Demonstrasi/Praktik)
            Route::get('/ia01/{id_sertifikasi}', [\App\Http\Controllers\IA01Controller::class, 'showView'])->name('ia01.admin.view');
        });

    // ======================================================
    // 4. AREA ASESOR
    // ======================================================
    Route::middleware(['role:asesor'])
        ->prefix('asesor')
        ->name('asesor.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index');
            Route::get('/notifikasi', [AsesorDashboardController::class, 'semuaNotifikasi'])->name('notifikasi.index');

            // Profil Self
            Route::get('/profil', [AsesorSelfProfileController::class, 'show'])->name('profil');
            Route::post('/update', [AsesorSelfProfileController::class, 'updateAsesorAjax'])->name('update.ajax');

            // Placeholders
            Route::get('/profile_tinjauan', fn() => view('profile_asesor.asesor_profile_tinjauan'))->name('profile_tinjauan');
            Route::get('/profile_tracker', fn() => view('profile_asesor.asesor_profile_tracker'))->name('profile_tracker');

            // Manajemen Jadwal & Asesi
            Route::get('/jadwal', [AsesorJadwalController::class, 'index'])->name('jadwal.index');
            Route::get('/daftar-asesi/{id_jadwal}', [AsesorJadwalController::class, 'showAsesi'])->name('daftar_asesi');
            Route::get('/tracker/{id_sertifikasi_asesi}', [AsesiTrackerController::class, 'show'])->name('tracker');

            // Daftar Hadir & BA
            Route::controller(AsesorJadwalController::class)->group(function () {
                Route::get('/daftar-hadir/{id_jadwal}', 'daftarHadir')->name('daftar_hadir');
                Route::post('/daftar-hadir/{id_jadwal}/simpan', 'storeKehadiran')->name('simpan_kehadiran');
                Route::get('/daftar-hadir/pdf/{id_jadwal}', 'exportPdfdaftarhadir')->name('daftar_hadir.pdf');

                // Asesmen Links
                Route::get('/jadwal/{id_jadwal}/ak05', 'ak05')->name('ak05');
                Route::post('/ak05/store/{id_jadwal}', 'storeAk05')->name('ak05.store');

                Route::get('/jadwal/{id_jadwal}/ak06', 'ak06')->name('ak06');
                Route::post('/ak06/store/{id_jadwal}', 'storeAk06')->name('ak06.store');

                Route::get('/asesmen/{id_sertifikasi_asesi}/ak07', 'ak07')->name('ak07');
                Route::post('/ak07/store/{id_sertifikasi_asesi}', 'storeAk07')->name('fr-ak-07.store');
            });

            // AK-02
            Route::get('/asesmen/ak02/{id_asesi}', [Ak02Controller::class, 'edit'])->name('ak02.edit');
            Route::put('/asesmen/ak02/{id_asesi}', [Ak02Controller::class, 'update'])->name('ak02.update');

            // APL-02 (Verifikasi)
            Route::get('/apl02/{id}', [PraasesmenController::class, 'view'])->name('apl02');

            // IA-06 Penilaian
            Route::get('/penilaian/ia-06/{id}', [IA06Controller::class, 'asesorShow'])->name('ia06.edit');
            Route::put('/penilaian/ia-06/{id}', [IA06Controller::class, 'asesorStorePenilaian'])->name('ia06.update');
        });

    // ======================================================
    // Berita Acara - Shared Route (Asesor & Admin)
    // ======================================================
    Route::middleware(['role:asesor,admin'])
        ->prefix('asesor')
        ->name('asesor.')
        ->group(function () {
            Route::controller(AsesorJadwalController::class)->group(function () {
                Route::get('/berita-acara/{id_jadwal}', 'beritaAcara')->name('berita_acara');
                Route::get('/berita-acara/pdf/{id_jadwal}', 'exportPdfberitaAcara')->name('berita_acara.pdf');
            });
        });

    // ======================================================
    // 5. AREA ASESI
    // ======================================================
    Route::middleware(['role:asesi'])
        ->prefix('asesi')
        ->name('asesi.')
        ->group(function () {
            // Dashboard / Riwayat
            Route::get('/riwayat-sertifikasi', [RiwayatSertifikasiController::class, 'index'])->name('riwayat.index');
            Route::get('/dashboard', [AsesiDashboardController::class, 'index'])->name('dashboard');

            // Profil
            Route::controller(AsesiSelfProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
                Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
            });

            // APL-01 & Pendaftaran
            Route::get('/data_sertifikasi/{id_sertifikasi}', [DataSertifikasiAsesiController::class, 'showFormulir'])->name('data.sertifikasi');
            Route::get('/bukti_pemohon/{id_sertifikasi}', [BuktiKelengkapanController::class, 'showBuktiPemohon'])->name('bukti.pemohon');
            Route::get('/halaman-tanda-tangan/{id_sertifikasi}', [TandaTanganAPIController::class, 'showTandaTangan'])->name('show.tandatangan');
            Route::get('/formulir-selesai', fn() => 'BERHASIL DISIMPAN!')->name('form.selesai');

            // APL-02
            Route::get('/pra-asesmen/{id_sertifikasi}', [PraasesmenController::class, 'index'])->name('apl02.view');

            // Tracker
            Route::controller(TrackerController::class)->group(function () {
                Route::get('/tracker/{jadwal_id?}', 'index')->name('tracker');
                Route::get('/pendaftaran-selesai/{id_sertifikasi}', 'pendaftaranSelesai')->name('pendaftaran.selesai');
                Route::get('/pra-asesmen-selesai/{id_sertifikasi}', 'praAsesmenSelesai')->name('pra_asesmen.selesai');
                Route::get('/persetujuan-selesai/{id_sertifikasi}', 'persetujuanSelesai')->name('persetujuan.selesai');
                Route::post('/daftar-jadwal', 'daftarJadwal')->name('daftar.jadwal');
            });

            // Jadwal & Konfirmasi
            Route::get('/jadwal-tuk/{id_sertifikasi}', [JadwalTukAPIController::class, 'show'])->name('show.jadwal_tuk');
            Route::get('/kerahasiaan/fr-ak01/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'show'])->name('kerahasiaan.fr_ak01');

            // Pembayaran
            Route::controller(PaymentController::class)->group(function () {
                Route::get('/bayar/{id_sertifikasi}', 'createTransaction')->name('payment.create');
                Route::get('/pembayaran_diproses', 'processed')->name('pembayaran_diproses');
                Route::get('/pembayaran_batal', 'paymentCancel')->name('payment.cancel');
                Route::get('/payment/{id_sertifikasi}/invoice', 'downloadInvoice')->name('payment.invoice');
            });

            // Asesmen Screens
            Route::get('/asesmen/ia05/{id_sertifikasi}', [AsesmenPilihanGandaController::class, 'indexPilihanGanda'])->name('asesmen.ia05.view');
            Route::get('/asesmen/ia06/{id_sertifikasi}', [AsesmenEsaiController::class, 'indexEsai'])->name('asesmen.ia06.view');

            // IA-06 Form
            Route::get('/asesmen/ia-06/{id}', [IA06Controller::class, 'asesiShow'])->name('ia06.index');
            Route::put('/asesmen/ia-06/{id}', [IA06Controller::class, 'asesiStoreJawaban'])->name('ia06.update');

            // IA-02, IA-03, IA-07, IA-11
            Route::get('/ia02/{id_sertifikasi}', [Ia02AsesiController::class, 'index'])->name('ia02.index');
            Route::post('/ia02/{id_sertifikasi}/next', [Ia02AsesiController::class, 'next'])->name('ia02.next');
            Route::get('/ia03/{id_data_sertifikasi_asesi}', [IA03Controller::class, 'index'])->name('ia03.index');
            Route::get('/asesi/ia07/{id_sertifikasi}', [Ia07AsesiController::class, 'index'])->name('ia07.index');
            Route::get('/ia11/{id_data_sertifikasi_asesi}', [IA11Controller::class, 'show'])->name('ia11.index');
            Route::post('/ia11/store', [IA11Controller::class, 'store'])->name('ia11.store');
            Route::put('/ia11/{id}', [IA11Controller::class, 'update'])->name('ia11.update');
            Route::delete('/ia11/{id}', [IA11Controller::class, 'destroy'])->name('ia11.destroy');
            Route::get('/asesmen/fr-ia-09/{id}', [AssessmenFRIA09Controller::class, 'index'])->name('asesmen.fr_ia_09.index');

            // Umpan Balik & Banding
            Route::get('/umpan-balik/{id}', [Ak03Controller::class, 'index'])->name('ak03.index');
            Route::post('/umpan-balik/store/{id}', [Ak03Controller::class, 'store'])->name('ak03.store');
            Route::get('/banding/fr-ak04/{id_sertifikasi}', [APIBandingController::class, 'show'])->name('banding.fr_ak04');

            // Cetak
            Route::get('/cetak/apl01/{id_data_sertifikasi}', [Apl01PdfController::class, 'generateApl01'])->name('cetak.apl01');
            Route::get('/cetak/apl02/{id_sertifikasi}', [Apl02PdfController::class, 'generateApl02'])->name('cetak.apl02');
            Route::get('/cetak/ak01/{id_sertifikasi}', [Ak01PdfController::class, 'generateAk01'])->name('cetak.ak01');
            Route::get('/kartu-peserta/{id_sertifikasi}', [KartuPesertaPdfController::class, 'generateKartuPeserta'])->name('pdf.kartu_peserta');
        });

    // ======================================================
    // 6. TRAFFIC COP (Role Redirection)
    // ======================================================

    // Tunggu Verifikasi (Asesor)
    Route::get('/tunggu-verifikasi', function () {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }
        if ($user->role->nama_role !== 'asesor') {
            return redirect()->route('home.index');
        }
        if ($user->asesor?->status_verifikasi === 'approved') {
            return redirect()->route('home.index');
        }
        return view('auth.verification-asesor');
    })->name('auth.wait');

    // Home Logic
    Route::get('/home', function (Request $request) {
        $user = Auth::user();
        $roleName = $user->role->nama_role ?? null;

        if ($roleName === 'asesi') {
            if (!$user->asesi) {
                // If Asesi profile missing, logout/force fill
                return redirect()->route('asesi.profile.edit')->with('warning', 'Silakan lengkapi profil Anda.');
            }
            return redirect()->route('asesi.riwayat.index');
        } elseif ($roleName === 'asesor') {
            $status = $user->asesor?->status_verifikasi;
            if (!$user->asesor || $status !== 'approved') {
                // Redirect to verification waiting page instead of logging out
                return redirect()->route('auth.wait');
            }
            return redirect()->route('asesor.dashboard');
        } elseif ($roleName === 'admin' || $roleName === 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        // Fallback
        Auth::logout();
        return redirect('/login')->with('error', 'Role Anda tidak valid.');
    })->name('home.index');
});
