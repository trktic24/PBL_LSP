<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IA02Controller;
use App\Http\Controllers\IA05Controller;
use App\Http\Controllers\IA07Controller;
use App\Http\Controllers\IA10Controller;
use App\Http\Controllers\APL01Controller;
use App\Http\Controllers\Admin\DetailSkemaController;
use App\Http\Controllers\Admin\AsesiController;
use App\Http\Controllers\Admin\SkemaController;
use App\Http\Controllers\Admin\AsesorController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\Mapa02Controller;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\FrMapa01Controller;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TukAdminController;
use App\Http\Controllers\Admin\DaftarHadirController;
use App\Http\Controllers\Asesi\ProfileController as AsesiProfileController;
use App\Http\Controllers\Asesi\RiwayatSertifikasiController;

use App\Http\Controllers\Asesi\TrackerController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Asesi\Apl01PdfController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Asesi\IA03\IA03Controller;
use App\Http\Controllers\Asesi\IA11\IA11Controller;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Asesi\IA02\Ia02AsesiController;
use App\Http\Controllers\Asesi\IA07\Ia07AsesiController;
use App\Http\Controllers\Asesi\Apl02\PraasesmenController;
use App\Http\Controllers\Asesi\umpan_balik\Ak03Controller;
use App\Http\Controllers\Auth\PasswordResetLinkController;
// --- Controllers Asesi Import ---
use App\Http\Controllers\Asesi\IA11\PerformaIA11Controller;
use App\Http\Controllers\Asesi\Ak04API\APIBandingController;
use App\Http\Controllers\Asesi\pembayaran\PaymentController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Asesi\asesmen\AsesmenEsaiController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Asesi\IA11\SpesifikasiIA11Controller;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Asesi\JadwalTukAPI\JadwalTukAPIController;
use App\Http\Controllers\Asesi\asesmen\AsesmenPilihanGandaController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Asesi\DashboardController as AsesiDashboardController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\TandaTanganAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\BuktiKelengkapanController;
use App\Http\Controllers\Asesor\DashboardController as AsesorDashboardController;
use App\Http\Controllers\Asesi\KerahasiaanAPI\PersetujuanKerahasiaanAPIController;
use App\Http\Controllers\Asesi\FormulirPendaftaranAPI\DataSertifikasiAsesiController;

// ======================================================
// --- RUTE GUEST (YANG BELUM LOGIN) ---
// ======================================================
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

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

// --- RUTE GOOGLE AUTH ---
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// ======================================================
// --- RUTE YANG WAJIB LOGIN ('auth') ---
// ======================================================
Route::middleware('auth')->group(function () {
    // ======================================================
    // 1. GENERAL AUTH ROUTES
    // ======================================================
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Route generic profile removed. Separate routes per role used instead.

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    // ======================================================
    // 2. SHARED / MIXED ROUTES (IA FORMS)
    // ======================================================
    /*
    |--------------------------------------------------------------------------
    | FORMULIR ASESMEN (IA)
    |--------------------------------------------------------------------------
    */

    // FR.IA.02
    Route::get('/fr-ia-02/{id}', [IA02Controller::class, 'show'])->name('fr-ia-02.show');
    Route::post('/fr-ia-02/{id}', [IA02Controller::class, 'store'])->name('fr-ia-02.store');
    Route::get('/fr-ia-02', fn() => view('frontend.FR_IA_02'))->name('FR_IA_02');

    // FR.IA.10
    Route::get('/fr-ia-10/{id_asesi}', [IA10Controller::class, 'create'])->name('fr-ia-10.create');
    Route::post('/fr-ia-10', [IA10Controller::class, 'store'])->name('fr-ia-10.store');
    Route::get('/FR-IA-10-view', fn() => view('frontend.FR_IA_10'))->name('FR-IA-10');

    // FR.IA.06 (Statis/View)
    Route::get('/fr-ia-06-c', fn() => view('frontend.fr_IA_06_c'))->name('fr_IA_06_c');
    Route::get('/fr-ia-06-a', fn() => view('frontend.fr_IA_06_a'))->name('fr_IA_06_a');
    Route::get('/fr-ia-06-b', fn() => view('frontend.fr_IA_06_b'))->name('fr_IA_06_b');

    // FR.IA.07
    Route::get('/fr-ia-07', fn() => view('frontend.FR_IA_07'))->name('FR_IA_07');

    /*
    | FORMULIR IA-05 (Kompleks dengan Role Middleware)
    */
    // Form A: Soal
    Route::middleware(['role:admin,asesor,asesi'])->group(function () {
        Route::get('/fr-ia-05-a/{id_asesi}', [IA05Controller::class, 'showSoalForm'])->name('FR_IA_05_A');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-a/store-soal', [IA05Controller::class, 'storeSoal'])->name('ia-05.store.soal');
    });
    Route::middleware(['role:asesi'])->group(function () {
        Route::post('/fr-ia-05-a/store-jawaban/{id_asesi}', [IA05Controller::class, 'storeJawabanAsesi'])->name('ia-05.store.jawaban');
    });

    // Form B: Kunci Jawaban
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-b', [IA05Controller::class, 'showKunciForm'])->name('FR_IA_05_B');
    });
    Route::middleware(['role:admin'])->group(function () {
        Route::post('/fr-ia-05-b', [IA05Controller::class, 'storeKunci'])->name('ia-05.store.kunci');
    });

    // Form C: Penilaian
    Route::middleware(['role:admin,asesor'])->group(function () {
        Route::get('/fr-ia-05-c/{id_asesi}', [IA05Controller::class, 'showJawabanForm'])->name('FR_IA_05_C');
    });
    Route::middleware(['role:asesor'])->group(function () {
        Route::post('/fr-ia-05-c/store-penilaian/{id_asesi}', [IA05Controller::class, 'storePenilaianAsesor'])->name('ia-05.store.penilaian');
    });

    //APL01
    Route::get('/apl-01-1/view/{id}', [APL01Controller::class, 'step1'])->name('APL_01_1');
    Route::post('/apl-01-1/store', [APL01Controller::class, 'storeStep1'])->name('apl01_1.store');
    Route::get('/apl-01-2/view/{id}', [APL01Controller::class, 'step2'])->name('APL_01_2');
    Route::post('/apl-01-2/store', [APL01Controller::class, 'storeStep2'])->name('apl01_2.store');
    Route::get('/apl-01-3/view/{id}', [APL01Controller::class, 'step3'])->name('APL_01_3');

    //MAPA01
    Route::get('/mapa01/show/{id}', [FrMapa01Controller::class, 'index'])->name('mapa01.index');
    Route::post('/mapa01/store', [FrMapa01Controller::class, 'store'])->name('mapa01.store');

    //MAPA02
    Route::get('/mapa02/show/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'show'])->name('mapa02.show');
    Route::post('/mapa02/store/{id_data_sertifikasi_asesi}', [Mapa02Controller::class, 'store'])->name('mapa02.store');

    // ======================================================
    // 3. ROLE: ADMIN
    // ======================================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        
        // Dashboard
        Route::controller(AdminDashboardController::class)->group(function () {
            Route::get('/dashboard', 'index')->name('dashboard');    
        });
        
        // Notification
        Route::get('/notifications', function () {
            return view('notifications.notifications_admin');
        })->name('notifications');

            // Notification
            Route::get('/notifications', function () {
                return view('notifications.notifications_admin');
            })->name('notifications');

            // Profile Admin
            Route::get('/profile_admin', function () {
                return view('profile.profile_admin');
            })->name('profile_admin');

            // Rute profil bawaan Laravel (Admin context)
            // Rute profil bawaan Laravel (Admin context)
            Route::controller(AdminProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
                Route::put('/profile/password', 'updatePassword')->name('profile.password.update');
            });

            // Master - Skema
            Route::controller(SkemaController::class)
                ->prefix('master/skema')
                ->group(function () {
                    Route::get('/', 'index')->name('master_skema');
                    Route::get('/add', 'create')->name('add_skema');
                    Route::post('/add', 'store')->name('add_skema.store');
                    Route::get('/edit/{id_skema}', 'edit')->name('edit_skema');
                    Route::patch('/update/{id_skema}', 'update')->name('update_skema');
                    Route::delete('/delete/{id_skema}', 'destroy')->name('delete_skema');
                });

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
                });

            // Master - Asesor
            Route::get('/master_asesor', [AsesorController::class, 'index'])->name('master_asesor');
            Route::get('/add_asesor1', [AsesorController::class, 'createStep1'])->name('add_asesor1');
            Route::post('/add_asesor1', [AsesorController::class, 'postStep1'])->name('add_asesor1.post');
            Route::get('/add_asesor2', [AsesorController::class, 'createStep2'])->name('add_asesor2');
            Route::post('/add_asesor2', [AsesorController::class, 'postStep2'])->name('add_asesor2.post');
            Route::get('/add_asesor3', [AsesorController::class, 'createStep3'])->name('add_asesor3');
            Route::post('/asesor/store', [AsesorController::class, 'store'])->name('asesor.store');
            Route::get('/edit-asesor-step-1/{id_asesor}', [AsesorController::class, 'editStep1'])->name('edit_asesor1');
            Route::patch('/update-asesor-step-1/{id_asesor}', [AsesorController::class, 'updateStep1'])->name('asesor.update.step1');
            Route::get('/edit-asesor-step-2/{id_asesor}', [AsesorController::class, 'editStep2'])->name('edit_asesor2');
            Route::patch('/update-asesor-step-2/{id_asesor}', [AsesorController::class, 'updateStep2'])->name('asesor.update.step2');
            Route::get('/edit-asesor-step-3/{id_asesor}', [AsesorController::class, 'editStep3'])->name('edit_asesor3');
            Route::patch('/update-asesor-step-3/{id_asesor}', [AsesorController::class, 'updateStep3'])->name('asesor.update.step3');
            Route::delete('/asesor/{id_asesor}', [AsesorController::class, 'destroy'])->name('asesor.destroy');

            // Master - Asesi
            Route::controller(AsesiController::class)
                ->prefix('master/asesi')
                ->group(function () {
                    Route::get('/', 'index')->name('master_asesi');
                    Route::get('/add', 'create')->name('add_asesi');
                    Route::post('/add', 'store')->name('add_asesi.store');
                    Route::get('/edit/{id_asesi}', 'edit')->name('edit_asesi');
                    Route::patch('/update/{id_asesi}', 'update')->name('update_asesi');
                    Route::delete('/delete/{id_asesi}', 'destroy')->name('delete_asesi');
                });

            // Master - Schedule
            Route::controller(ScheduleController::class)
                ->prefix('master/schedule')
                ->group(function () {
                    Route::get('/', 'index')->name('master_schedule');
                    Route::get('/add', 'create')->name('add_schedule');
                    Route::post('/add', 'store')->name('add_schedule.store');
                    Route::get('/edit/{id_jadwal}', 'edit')->name('edit_schedule');
                    Route::patch('/update/{id_jadwal}', 'update')->name('update_schedule');
                    Route::delete('/delete/{id_jadwal}', 'destroy')->name('delete_schedule');
                    Route::get('/attendance/{id_jadwal}', [DaftarHadirController::class, 'index'])->name('schedule.attendance');
                });
            Route::get('/schedule_admin', [ScheduleController::class, 'showCalendar'])->name('schedule_admin');

            // TUK
            Route::controller(TukAdminController::class)
                ->prefix('master/tuk')
                ->group(function () {
                    Route::get('/', 'index')->name('master_tuk');
                    Route::get('/add', 'create')->name('add_tuk');
                    Route::post('/add', 'store')->name('add_tuk.store');
                    Route::get('/edit/{id}', 'edit')->name('edit_tuk');
                    Route::patch('/update/{id}', 'update')->name('update_tuk');
                    Route::delete('/delete/{id}', 'destroy')->name('delete_tuk');
                });

            // Master - Category
            Route::controller(CategoryController::class)
                ->prefix('master/category')
                ->group(function () {
                    Route::get('/', 'index')->name('master_category');
                    Route::get('/add', 'create')->name('add_category');
                    Route::post('/add', 'store')->name('add_category.store');
                    Route::get('/edit/{category}', 'edit')->name('edit_category');
                    Route::patch('/update/{category}', 'update')->name('update_category');
                    Route::delete('/delete/{category}', 'destroy')->name('delete_category');
                });
        });

    // ======================================================
    // 4. ROLE: ASESOR
    // ======================================================
    Route::middleware(['role:asesor'])
        ->prefix('asesor')
        ->name('asesor.')
        ->group(function () {
            // Dashboard
            Route::get('/dashboard', [AsesorDashboardController::class, 'index'])->name('dashboard');
            Route::get('/home', [AsesorDashboardController::class, 'index'])->name('home.index'); // Keep for backward compatibility if needed

            // Manajemen Jadwal & Asesi
            Route::get('/jadwal', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'index'])->name('jadwal.index');
            Route::get('/daftar-asesi/{id_jadwal}', [\App\Http\Controllers\Asesor\AsesorJadwalController::class, 'showAsesi'])->name('daftar_asesi');
            Route::get('/tracker/{id_sertifikasi_asesi}', [TrackerController::class, 'show'])->name('tracker');

            // Tools
            Route::get('/laporan', fn() => view('frontend.laporan'))->name('laporan');

            // Profile Asesor
            Route::get('/profil', [\App\Http\Controllers\Asesor\ProfileController::class, 'show'])->name('profil');
            Route::get('/{id_asesor}/bukti', [AsesorController::class, 'showBukti'])->name('bukti');
            // Route::get('/{id_asesor}/profile', [AsesorController::class, 'showProfile'])->name('profile'); // Replaced by /profil
            Route::get('/profile_tinjauan', function () {
                return view('profile_asesor.asesor_profile_tinjauan');
            })->name('profile_tinjauan');
            Route::get('/profile_tracker', function () {
                return view('profile_asesor.asesor_profile_tracker');
            })->name('profile_tracker');

            // Update Profile Asesor (Ajax)
            Route::post('/update', [\App\Http\Controllers\Asesor\ProfileController::class, 'updateAsesorAjax'])->name('update.ajax');
        });

    // ======================================================
    // 5. ROLE: ASESI
    // ======================================================
    Route::middleware(['role:asesi'])
        ->prefix('asesi')
        ->name('asesi.')
        ->group(function () {
            // Dashboard (Redirected here usually)
            // Route::get('/dashboard', [AsesiDashboardController::class, 'index'])->name('dashboard');

            // --- Profile Asesi ---
            Route::controller(AsesiProfileController::class)->group(function () {
                Route::get('/profile', 'edit')->name('profile.edit');
                Route::patch('/profile', 'update')->name('profile.update');
                Route::delete('/profile', 'destroy')->name('profile.destroy');
                Route::put('/profile/password', 'updatePassword')->name('profile.password.update'); // Jika ada update PW
            });

            // --- A. Tracker ---
            Route::controller(TrackerController::class)->group(function () {
                Route::get('/tracker/{jadwal_id?}', 'index')->name('tracker');
                Route::post('/daftar-jadwal', 'daftarJadwal')->name('daftar.jadwal');
                Route::get('/pendaftaran-selesai', 'pendaftaranSelesai')->name('pendaftaran.selesai');
            });

            // --- Riwayat Sertifikasi (REPLACEMENT FOR DASHBOARD) ---
            Route::get('/riwayat-sertifikasi', [RiwayatSertifikasiController::class, 'index'])->name('riwayat.index');

            // --- B. Formulir APL-01 (Pendaftaran) ---
            // Menggunakan Controller yang baru saja kita rapikan
            Route::get('/data_sertifikasi/{id_sertifikasi}', [DataSertifikasiAsesiController::class, 'showFormulir'])->name('data.sertifikasi');
            Route::get('/bukti_pemohon/{id_sertifikasi}', [BuktiKelengkapanController::class, 'showBuktiPemohon'])->name('bukti.pemohon');
            Route::get('/halaman-tanda-tangan/{id_sertifikasi}', [TandaTanganAPIController::class, 'showTandaTangan'])->name('show.tandatangan');

            Route::get('/formulir-selesai', fn() => 'BERHASIL DISIMPAN! Ini halaman selanjutnya.')->name('form.selesai');

            // --- C. Formulir APL-02 (Pra-Asesmen) ---
            Route::get('/pra-asesmen/{id_sertifikasi}', [PraasesmenController::class, 'index'])->name('apl02.view');

            // --- D. Persetujuan & Jadwal TUK ---
            // Persetujuan Kerahasiaan (FR.AK.01)
            Route::get('/kerahasiaan/fr-ak01/{id_sertifikasi}', [PersetujuanKerahasiaanAPIController::class, 'show'])->name('kerahasiaan.fr_ak01');
            // Konfirmasi Jadwal TUK
            Route::get('/jadwal-tuk/{id_sertifikasi}', [JadwalTukAPIController::class, 'show'])->name('show.jadwal_tuk');

            // --- E. Asesmen / Ujian (IA.05 & IA.06) ---
            Route::get('/asesmen/ia05/{id_sertifikasi}', [AsesmenPilihanGandaController::class, 'indexPilihanGanda'])->name('asesmen.ia05.view');
            Route::get('/asesmen/ia06/{id_sertifikasi}', [AsesmenEsaiController::class, 'indexEsai'])->name('asesmen.ia06.view');

            // --- F. Pasca Asesmen (Banding & Umpan Balik) ---
            // Umpan Balik (AK.03)
            Route::get('/umpan-balik/{id}', [Ak03Controller::class, 'index'])->name('ak03.index');
            Route::post('/umpan-balik/store/{id}', [Ak03Controller::class, 'store'])->name('ak03.store');
            // Banding (AK.04)
            Route::get('/banding/fr-ak04/{id_sertifikasi}', [APIBandingController::class, 'show'])->name('banding.fr_ak04');

            // --- G. Pembayaran (Midtrans) ---
            Route::controller(PaymentController::class)->group(function () {
                Route::get('/bayar/{id_sertifikasi}', 'createTransaction')->name('payment.create');
                Route::get('/pembayaran_diproses', 'processed')->name('pembayaran_diproses'); // Callback sukses
                Route::get('/pembayaran_batal', 'paymentCancel')->name('payment.cancel'); // Callback batal
                Route::get('/payment/{id_sertifikasi}/invoice', 'downloadInvoice')->name('payment.invoice');
            });

            // --- H. Utilities (PDF & Cetak) ---
            Route::get('/cetak/apl01/{id_data_sertifikasi}', [Apl01PdfController::class, 'generateApl01'])->name('pdf.apl01');

            // --- IA.01 sementara (biar tidak error) ---
            Route::get('/ia01/{id_sertifikasi}', function ($id_sertifikasi) {
                return 'HALAMAN IA01 BELUM DIBUAT — ID: ' . $id_sertifikasi;
            })->name('ia01.index');

            // --- ROUTE FR.IA.02 (TUGAS PRAKTIK / DEMONSTRASI) ---

            // 1. Menampilkan halaman IA02 (READ-ONLY)
            // id_sertifikasi = ID Data Sertifikasi Asesi
            Route::get('/ia02/{id_sertifikasi}', [Ia02AsesiController::class, 'index'])->name('ia02.index');

            // 2. Tombol "Selanjutnya" → redirect ke IA03
            Route::post('/ia02/{id_sertifikasi}/next', [Ia02AsesiController::class, 'next'])->name('ia02.next');

            // Halaman utama IA03 (list pertanyaan + identitas lengkap)
            Route::get('/ia03/{id_data_sertifikasi_asesi}', [IA03Controller::class, 'index'])->name('ia03.index');

            // Halaman detail satu pertanyaan (opsional)
            Route::get('/ia03/detail/{id}', [IA03Controller::class, 'show'])->name('ia03.show');

            // --- ROUTE FR.IA.07 (PERTANYAAN LISAN) ---

            // 1. Route untuk Menampilkan Form (GET)
            // Parameter {id_sertifikasi} diperlukan agar Controller tahu data siapa yang ditampilkan
            Route::get('/asesi/ia07/{id_sertifikasi}', [Ia07AsesiController::class, 'index'])->name('ia07.index');

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

    // ======================================================
    // 6. TRAFFIC COP (HOME REDIRECT)
    // ======================================================
    Route::get('/dashboard', function () {
        return redirect()->route('home.index');
    })->name('dashboard');

    Route::get('/home', function (Request $request) {
        $user = Auth::user();
        $roleName = $user->role->nama_role ?? null;

        // 1. JIKA ASESI
        if ($roleName === 'asesi') {
            // [MODIFIED] Asesi sekarang langsung ke Riwayat, bukan Dashboard
            return redirect()->route('asesi.riwayat.index');
        }

        // 2. JIKA ASESOR
        elseif ($roleName === 'asesor') {
            // --- CEK STATUS VERIFIKASI ---
            $status = $user->asesor?->status_verifikasi;
            if (!$user->asesor || $status !== 'approved') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('error', 'Akun Anda belum diverifikasi oleh Admin. Silakan tunggu persetujuan.');
            }
            return redirect()->route('asesor.dashboard');
        }

        // 3. JIKA ADMIN
        elseif ($roleName === 'admin' || $roleName === 'superadmin') {
            return app(AdminDashboardController::class)->index($request);
        }

        // 4. JIKA ROLE TIDAK DIKENALI
        Auth::logout();
        return redirect('/login')->with('error', 'Role Anda tidak terdefinisi.');
    })->name('home.index');
});

// Halaman tunggu verifikasi (Opsional, jika Anda ingin redirect ke sini alih-alih logout)
// Saya sesuaikan juga kolomnya jadi 'is_verified' untuk jaga-jaga.
Route::get('/tunggu-verifikasi', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }
    if ($user->role->nama_role !== 'asesor') {
        return redirect()->route('home.index');
    }

    // Cek status_verifikasi
    if ($user->asesor?->status_verifikasi === 'approved') {
        return redirect()->route('home.index');
    }

    return view('auth.verification-asesor');
})->name('auth.wait');
