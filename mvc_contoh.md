
mvc
PENJELASAN KOMPONEN MVC PROJEK WEBAPP OSCE TI2B
📚 Daftar Isi
Pengenalan MVC
Komponen Model
Komponen View
Komponen Controller
Layer Tambahan dalam Projek
Alur Kerja MVC dengan Contoh
Struktur Direktori Lengkap
📊 Rangkuman Perbandingan Komponen MVC
🎓 Kesimpulan
Pengenalan MVC
MVC (Model-View-Controller) adalah pola arsitektur perangkat lunak yang memisahkan aplikasi menjadi 3 komponen utama:

Model: Mengelola data dan logika bisnis
View: Menampilkan data kepada pengguna (User Interface)
Controller: Menghubungkan Model dan View, menangani request pengguna
Projek ini menggunakan Laravel Framework dengan pola MVC yang diperkaya dengan layer tambahan seperti Services dan Middleware.

1. MODEL (M)
📍 Lokasi
app/Models/
🎯 Fungsi Utama
Model adalah representasi dari tabel database dalam bentuk class PHP. Model bertanggung jawab untuk:

Berinteraksi dengan database (CRUD - Create, Read, Update, Delete)
Mendefinisikan relasi antar tabel
Mendefinisikan atribut yang dapat diisi (fillable)
Mengelola casting tipe data
📂 Daftar Model dalam Projek
No	Nama Model	Tabel Database	Fungsi
1	Mahasiswa.php	mahasiswa	Data mahasiswa (nim, nama, kelas, prodi)
2	Penguji.php	penguji	Data dosen penguji OSCE
3	Admin.php	admin	Data administrator sistem
4	Osce.php	osce	Data ujian OSCE (judul, tanggal, durasi)
5	Stase.php	stase	Data stase/station ujian
6	AspekPenilaian.php	aspek_penilaian	Aspek yang dinilai dalam OSCE
7	NilaiOsce.php	nilai_osce	Data nilai mahasiswa
8	Enrollment.php	enrollment	Pendaftaran mahasiswa per tahun akademik
9	EnrollmentOsce.php	enrollment_osce	Pendaftaran mahasiswa ke OSCE tertentu
10	Pengguna.php	pengguna	Data pengguna sistem (username, password, role)
11	TahunAkademik.php	tahun_akademik	Data tahun akademik
12	MataKuliah.php	mata_kuliah	Data mata kuliah
13	Blok.php	blok	Data blok pembelajaran
14	Ruang.php	ruang	Data ruang ujian
15	OsceStase.php	osce_stase	Relasi OSCE dengan Stase
16	PoinAspekPenilaian.php	poin_aspek_penilaian	Poin penilaian tiap aspek
17	TujuanPembelajaran.php	tujuan_pembelajaran	Tujuan pembelajaran
18	LogoInstitusi.php	logo_institusi	Logo institusi
📝 Contoh Implementasi Model (Mahasiswa.php)
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'mahasiswa';

    // Primary key
    protected $primaryKey = 'id_mahasiswa';

    // Kolom yang boleh diisi mass assignment
    protected $fillable = [
        'id_pengguna',
        'nama',
        'nim',
        'kelas',
        'prodi',
        'status',
    ];

    // RELASI KE MODEL LAIN

    // Relasi Many-to-One dengan Pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna');
    }

    // Relasi One-to-Many dengan Enrollment
    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'id_mahasiswa'); 
    }

    // Relasi One-to-Many dengan EnrollmentOsce
    public function enrollment_osce()
    {
        return $this->hasMany(EnrollmentOsce::class, 'id_mahasiswa');
    }
}
🔗 Jenis-jenis Relasi dalam Model
belongsTo (Many-to-One): Satu mahasiswa memiliki satu pengguna
hasMany (One-to-Many): Satu mahasiswa memiliki banyak enrollment
belongsToMany (Many-to-Many): Digunakan dengan pivot table
hasOne (One-to-One): Satu record memiliki satu record terkait
2. VIEW (V)
📍 Lokasi
resources/js/pages/          # View React/Inertia.js (SPA)
resources/views/             # View Blade (PHP template)
🎯 Fungsi Utama
View bertanggung jawab untuk:

Menampilkan data kepada pengguna
Menerima input dari pengguna
Mengirim request ke Controller
Tidak mengandung logika bisnis (hanya logika presentasi)
📂 Struktur View dalam Projek
Projek ini menggunakan 2 jenis View:

A. View React/Inertia.js (Modern SPA)
Lokasi: resources/js/pages/

Struktur Folder:

resources/js/pages/
├── Admin/                    # View untuk Admin
│   ├── Dashboard.jsx
│   ├── MahasiswaPage.jsx
│   ├── TambahMahasiswa.jsx
│   ├── PengujiPage.jsx
│   ├── TambahPenguji.jsx
│   ├── OsceListPage.jsx
│   ├── TambahOsce.jsx
│   ├── OsceStasePage.jsx
│   ├── TambahOsceStase.jsx
│   ├── MenuKompetensi.jsx
│   ├── TambahKompetensi.jsx
│   ├── MenuAspekPenilaian.jsx
│   ├── OsceEnrollmentPage.jsx
│   ├── OsceJadwalPage.jsx
│   ├── TambahJadwalSesi.jsx
│   ├── RekapOscePage.jsx
│   ├── RekapSesiPage.jsx
│   └── RekapDetailPage.jsx
├── Penguji/                  # View untuk Penguji
│   ├── PengujiDashboard.jsx
│   ├── PengujiOsceList.jsx
│   ├── StaseAntrian.jsx
│   ├── LiveAntrian.jsx
│   ├── LiveRotasi.jsx
│   ├── LivePenilaian.jsx
│   ├── SubmitRubrik.jsx
│   ├── ViewNilaiDetail.jsx
│   ├── EditNilaiForm.jsx
│   ├── RekapMahasiswaPage.jsx
│   └── PengujiProfil.jsx
├── Mahasiswa/                # View untuk Mahasiswa
│   ├── DashboardMahasiswa.jsx
│   ├── JadwalMahasiswa.jsx
│   ├── ListNilaiMahasiswa.jsx
│   ├── NilaiMahasiswaDetail.jsx
│   └── ProfilMahasiswa.jsx
└── Home.jsx                  # Landing page
Total: 41 file View React

B. View Blade (Traditional PHP Template)
Lokasi: resources/views/

resources/views/
├── app.blade.php                        # Layout utama aplikasi
├── pdf/
│   ├── rekap_nilai.blade.php           # Template PDF rekap nilai
│   └── rekap_nilai_penguji.blade.php   # Template PDF rekap nilai penguji
└── vendor/
    └── scramble/
        └── docs.blade.php               # API documentation page
📝 Contoh View (MahasiswaPage.jsx - Snippet)
import React, { useState } from "react";
import { router, usePage } from "@inertiajs/react";

const MahasiswaPage = () => {
    const { mahasiswa, list_tahun, filters } = usePage().props;

    // Render tabel mahasiswa
    return (
        <div>
            <h1>Daftar Mahasiswa</h1>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Prodi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {mahasiswa.map((mhs, index) => (
                        <tr key={mhs.id_mahasiswa}>
                            <td>{index + 1}</td>
                            <td>{mhs.nim}</td>
                            <td>{mhs.nama}</td>
                            <td>{mhs.kelas}</td>
                            <td>{mhs.prodi}</td>
                            <td>
                                <button>Edit</button>
                                <button>Hapus</button>
                            </td>
                        </tr>
                    ))}
                </tbody>
            </table>
        </div>
    );
};

export default MahasiswaPage;
🎨 Teknologi View yang Digunakan
React.js: Library JavaScript untuk membuat UI
Inertia.js: Menghubungkan Laravel backend dengan React frontend
Tailwind CSS: Framework CSS untuk styling
Blade: PHP templating engine (untuk PDF dan layout)
3. CONTROLLER (C)
📍 Lokasi
app/Http/Controllers/
🎯 Fungsi Utama
Controller bertanggung jawab untuk:

Menerima HTTP request dari routing
Memanggil Service/Model untuk mendapatkan data
Memvalidasi input pengguna
Mengirim data ke View
Menangani redirect dan response
📂 Struktur Controller dalam Projek
app/Http/Controllers/
├── Controller.php                     # Base controller Laravel
├── AuthController.php                 # Autentikasi login/logout
├── AuthenticateApiController.php      # Autentikasi untuk API
├── Admin/                             # Controller untuk Admin
│   ├── AdminController.php
│   ├── MahasiswaController.php
│   ├── PengujiController.php
│   ├── OsceController.php
│   ├── StaseController.php
│   ├── KompetensiController.php
│   ├── AspekPenilaianController.php
│   ├── OsceEnrollmentController.php
│   ├── OsceJadwalController.php
│   └── RekapNilaiController.php
├── Penguji/                           # Controller untuk Penguji
│   ├── DashboardController.php
│   ├── OsceController.php
│   ├── HalamanPenilaianController.php
│   ├── AksiPenilaianController.php
│   ├── ViewNilaiController.php
│   ├── EditNilaiController.php
│   ├── RekapController.php
│   └── ProfilController.php
├── Mahasiswa/                         # Controller untuk Mahasiswa
│   ├── DashboardMahasiswaController.php
│   ├── JadwalMahasiswaController.php
│   ├── ListNilaiMahasiswaController.php
│   ├── NilaiMahasiswaController.php
│   └── ProfilMahasiswaController.php
└── Api/V1/                            # API Controllers
    └── Penguji/
        ├── AksiPenilaianApiController.php
        ├── RekapController.php
        ├── ProfilController.php
        └── ViewNilaiController.php
📝 Contoh Implementasi Controller (MahasiswaController.php)
<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use App\Services\Admin\MahasiswaService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class MahasiswaController extends Controller
{
    protected $service;

    // Dependency Injection: Service class di-inject ke controller
    public function __construct(MahasiswaService $service)
    {
        $this->service = $service;
    }

    /**
     * Menampilkan daftar mahasiswa (READ)
     */
    public function index(Request $request)
    {
        // Ambil parameter pencarian
        $search = $request->input('search');
        $angkatan = $request->input('angkatan');

        // Panggil service untuk mendapatkan data
        $mahasiswa = $this->service->getAll($search, $angkatan);

        // Render view dengan Inertia
        return Inertia::render('Admin/MahasiswaPage', [
            'mahasiswa' => $mahasiswa,
            'filters' => $request->only(['search', 'angkatan']),
        ]);
    }

    /**
     * Menampilkan form tambah mahasiswa
     */
    public function create()
    {
        return Inertia::render('Admin/TambahMahasiswa', [
            'mahasiswa' => null,
        ]);
    }

    /**
     * Menyimpan mahasiswa baru (CREATE)
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nim'   => 'required|string|max:20|unique:mahasiswa,nim',
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'prodi' => 'required|string|max:100',
            'angkatan' => 'required|string',
        ]);

        // Panggil service untuk menyimpan data
        $this->service->store($validated);

        // Redirect dengan pesan sukses
        return Redirect::route('admin.mahasiswa.index')
            ->with('success', 'Mahasiswa baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit mahasiswa
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        return Inertia::render('Admin/TambahMahasiswa', [
            'mahasiswa' => [
                'id_mahasiswa' => $mahasiswa->id_mahasiswa,
                'nim' => $mahasiswa->nim,
                'nama' => $mahasiswa->nama,
                'kelas' => $mahasiswa->kelas,
                'prodi' => $mahasiswa->prodi,
            ],
        ]);
    }

    /**
     * Update data mahasiswa (UPDATE)
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $validated = $request->validate([
            'nim'   => 'required|string|max:20|unique:mahasiswa,nim,' 
                       . $mahasiswa->id_mahasiswa . ',id_mahasiswa',
            'nama'  => 'required|string|max:255',
            'kelas' => 'required|string|max:50',
            'prodi' => 'required|string|max:100',
            'angkatan' => 'required|string',
        ]);

        $this->service->update($validated, $mahasiswa);

        return Redirect::route('admin.mahasiswa.index')
            ->with('success', 'Data mahasiswa berhasil diperbarui.');
    }

    /**
     * Hapus mahasiswa (DELETE)
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $this->service->delete($mahasiswa);

        return Redirect::back()
            ->with('success', 'Mahasiswa berhasil dihapus.');
    }
}
🔄 Method-method Standar dalam Controller
Method	HTTP Verb	Fungsi	Route Name
index()	GET	Menampilkan daftar data	*.index
create()	GET	Menampilkan form tambah	*.create
store()	POST	Menyimpan data baru	*.store
show()	GET	Menampilkan detail data	*.show
edit()	GET	Menampilkan form edit	*.edit
update()	PUT/PATCH	Mengupdate data	*.update
destroy()	DELETE	Menghapus data	*.destroy
Layer Tambahan dalam Projek
Selain MVC standar, projek ini menggunakan beberapa layer tambahan untuk memisahkan tanggung jawab dengan lebih baik:

1. 🛠️ SERVICE LAYER
📍 Lokasi
app/Services/
🎯 Fungsi
Service layer berisi logika bisnis aplikasi. Ini memisahkan logika dari Controller agar Controller tetap "thin" (ringan).

📂 Daftar Service
app/Services/
├── AuthService.php
├── EditNilaiService.php
├── JadwalMahasiswaService.php
├── OscePengujiService.php
├── ProfilMahasiswaService.php
├── Admin/
│   ├── AdminService.php
│   ├── AspekPenilaianService.php
│   ├── KompetensiService.php
│   ├── MahasiswaService.php
│   ├── OsceEnrollmentService.php
│   ├── OsceJadwalService.php
│   ├── OsceService.php
│   ├── PengujiService.php
│   ├── RekapService.php
│   └── StaseService.php
├── Mahasiswa/
│   └── NilaiMahasiswaService.php
└── Penguji/
    ├── AksiPenilaianService.php
    ├── HalamanPenilaianService.php
    └── RekapService.php
📝 Contoh Service (MahasiswaService.php - Snippet)
<?php

namespace App\Services\Admin;

use App\Models\Mahasiswa;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;

class MahasiswaService
{
    /**
     * Mengambil semua data mahasiswa dengan filter
     */
    public function getAll($search = null, $angkatan = null)
    {
        $mahasiswaQuery = Mahasiswa::query()->with(['enrollment.tahunAkademik']);

        // Filter pencarian
        $mahasiswaQuery->when($search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        });

        // Filter angkatan
        if ($angkatan && $angkatan !== 'SEMUA') {
            $mahasiswaQuery->whereHas('enrollment', function ($qEnroll) use ($angkatan) {
                $qEnroll->whereHas('tahunAkademik', function ($qTahun) use ($angkatan) {
                    $qTahun->where('tahun', $angkatan);
                });
            });
        }

        return $mahasiswaQuery->orderBy('nama', 'asc')->get();
    }

    /**
     * Menyimpan mahasiswa baru dengan database transaction
     */
    public function store($validated)
    {
        return DB::transaction(function () use ($validated) {
            // 1. Buat pengguna
            $pengguna = Pengguna::create([
                'username' => $validated['nim'],
                'password' => bcrypt($validated['nim']),
                'jenis_role' => 'mahasiswa',
            ]);

            // 2. Buat mahasiswa
            $mahasiswa = Mahasiswa::create([
                'id_pengguna' => $pengguna->id_pengguna,
                'nim'   => $validated['nim'],
                'nama'  => $validated['nama'],
                'kelas' => $validated['kelas'],
                'prodi' => $validated['prodi'],
                'status' => 'aktif',
            ]);

            // 3. Buat enrollment jika ada tahun akademik
            // ... (logika enrollment)

            return $mahasiswa;
        });
    }

    // Method update, delete, dll...
}
2. 🚦 MIDDLEWARE
📍 Lokasi
app/Http/Middleware/
🎯 Fungsi
Middleware adalah "filter" yang memproses HTTP request sebelum sampai ke Controller.

📂 Daftar Middleware
Middleware	Fungsi
GuestMiddleware.php	Mengecek apakah user belum login (untuk halaman login)
RoleMiddleware.php	Mengecek role user (admin/penguji/mahasiswa) untuk web
RoleApiMiddleware.php	Mengecek role user untuk API
HandleInertiaRequests.php	Menangani request Inertia.js
AuthenticateApiDocs.php	Autentikasi untuk API documentation
3. 🛣️ ROUTES (Routing)
📍 Lokasi
routes/
├── web.php      # Route untuk web interface
├── api.php      # Route untuk API endpoints
└── console.php  # Route untuk Artisan commands
🎯 Fungsi
Routes mendefinisikan "peta jalan" aplikasi, menghubungkan URL dengan Controller.

📝 Contoh Routes (web.php - Snippet)
// Route untuk Admin - Mahasiswa
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('mahasiswa', MahasiswaController::class);
    Route::post('mahasiswa/import', [MahasiswaController::class, 'import'])
        ->name('mahasiswa.import');
    Route::get('mahasiswa/template', [MahasiswaController::class, 'template'])
        ->name('mahasiswa.template');
});
Penjelasan:

middleware(['auth', 'role:admin']): Harus login dan role admin
prefix('admin'): URL akan menjadi /admin/...
name('admin.'): Nama route akan dimulai dengan admin.
Route::resource(): Membuat 7 route standar (index, create, store, show, edit, update, destroy)
4. 📦 IMPORTS & EXPORTS
📍 Lokasi
app/Imports/    # Import data dari Excel
app/Exports/    # Export data ke Excel
📂 Daftar File
app/Imports/
└── MahasiswaImport.php    # Import data mahasiswa dari Excel
app/Exports/
├── TemplateMahasiswaExport.php      # Template Excel mahasiswa
└── TemplatePengujiExport.php        # Template Excel penguji
5. 🗄️ DATABASE
📍 Lokasi
database/
├── migrations/    # Schema database
├── seeders/       # Data awal (dummy data)
└── factories/     # Factory untuk testing
Alur Kerja MVC dengan Contoh
Mari kita lihat bagaimana MVC bekerja dengan contoh konkret: "Admin menampilkan daftar mahasiswa"

📊 Flow Diagram
User Browser
    ↓
1. Request GET /admin/mahasiswa
    ↓
2. routes/web.php → Mencocokkan route
    ↓
3. Middleware [auth, role:admin] → Cek autentikasi & role
    ↓
4. MahasiswaController@index → Terima request
    ↓
5. MahasiswaService@getAll() → Ambil data dari database
    ↓
6. Model Mahasiswa → Query database dengan Eloquent
    ↓
7. Database → Kembalikan data
    ↓
8. Model → Kembalikan collection ke Service
    ↓
9. Service → Kembalikan data terformat ke Controller
    ↓
10. Controller → Kirim data ke View dengan Inertia
    ↓
11. Inertia → Render React component (MahasiswaPage.jsx)
    ↓
12. View → Tampilkan HTML/CSS/JS ke browser
    ↓
User melihat daftar mahasiswa
📝 Kode Lengkap untuk Setiap Layer
1️⃣ Route (routes/web.php)
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('mahasiswa', [MahasiswaController::class, 'index'])
            ->name('mahasiswa.index');
    });
2️⃣ Controller (MahasiswaController.php)
public function index(Request $request)
{
    $search = $request->input('search');
    $angkatan = $request->input('angkatan');

    $mahasiswa = $this->service->getAll($search, $angkatan);

    return Inertia::render('Admin/MahasiswaPage', [
        'mahasiswa' => $mahasiswa,
    ]);
}
3️⃣ Service (MahasiswaService.php)
public function getAll($search = null, $angkatan = null)
{
    $mahasiswaQuery = Mahasiswa::query()
        ->with(['enrollment.tahunAkademik']);

    $mahasiswaQuery->when($search, function ($query, $search) {
        $query->where('nim', 'like', "%{$search}%")
              ->orWhere('nama', 'like', "%{$search}%");
    });

    return $mahasiswaQuery->orderBy('nama', 'asc')->get();
}
4️⃣ Model (Mahasiswa.php)
class Mahasiswa extends Model
{
    protected $table = 'mahasiswa';
    protected $fillable = ['nama', 'nim', 'kelas', 'prodi'];

    public function enrollment()
    {
        return $this->hasMany(Enrollment::class, 'id_mahasiswa');
    }
}
5️⃣ View (MahasiswaPage.jsx)
const MahasiswaPage = () => {
    const { mahasiswa } = usePage().props;

    return (
        <div>
            <h1>Daftar Mahasiswa</h1>
            <table>
                {mahasiswa.map(mhs => (
                    <tr key={mhs.id_mahasiswa}>
                        <td>{mhs.nim}</td>
                        <td>{mhs.nama}</td>
                        <td>{mhs.kelas}</td>
                    </tr>
                ))}
            </table>
        </div>
    );
};
Struktur Direktori Lengkap
webapp_osce_ti2b/
│
├── app/                           # Aplikasi utama
│   ├── Http/
│   │   ├── Controllers/          # CONTROLLER (C)
│   │   │   ├── Admin/
│   │   │   ├── Penguji/
│   │   │   ├── Mahasiswa/
│   │   │   └── Api/
│   │   └── Middleware/           # Middleware (filter request)
│   │
│   ├── Models/                   # MODEL (M)
│   │   ├── Mahasiswa.php
│   │   ├── Penguji.php
│   │   ├── Osce.php
│   │   └── ...
│   │
│   ├── Services/                 # Business Logic Layer
│   │   ├── Admin/
│   │   ├── Penguji/
│   │   └── Mahasiswa/
│   │
│   ├── Imports/                  # Import Excel
│   ├── Exports/                  # Export Excel
│   └── Providers/                # Service Providers
│
├── resources/                     # Resources (View, Assets)
│   ├── js/
│   │   └── pages/                # VIEW (V) - React Components
│   │       ├── Admin/
│   │       ├── Penguji/
│   │       └── Mahasiswa/
│   │
│   ├── views/                    # VIEW (V) - Blade Templates
│   │   ├── app.blade.php
│   │   └── pdf/
│   │
│   └── css/                      # Styling
│
├── routes/                        # ROUTING
│   ├── web.php                   # Web routes
│   ├── api.php                   # API routes
│   └── console.php               # Console routes
│
├── database/                      # Database
│   ├── migrations/               # Schema database
│   ├── seeders/                  # Data awal
│   └── factories/                # Factory untuk testing
│
├── public/                        # Public assets
├── storage/                       # Storage (upload, logs, cache)
├── tests/                         # Testing
├── config/                        # Konfigurasi
│
├── .env                          # Environment variables
├── composer.json                 # PHP dependencies
├── package.json                  # Node.js dependencies
└── artisan                       # CLI tool Laravel
📊 Rangkuman Perbandingan Komponen MVC
Aspek	Model	View	Controller
Tanggung Jawab	Mengelola data & database	Menampilkan UI	Menghubungkan Model & View
Lokasi	app/Models/	resources/js/pages/	app/Http/Controllers/
Teknologi	Eloquent ORM (PHP)	React.js + Inertia.js + Tailwind CSS + Blade php	PHP (Laravel)
Contoh File	Mahasiswa.php	MahasiswaPage.jsx	MahasiswaController.php
Interaksi	Database ↔ Model	View → User	Request → Controller → Response
Logika	Relasi data, casting	Presentasi data saja	Orchestration & validasi
🎓 Kesimpulan
Projek webapp_osce_ti2b mengimplementasikan pola MVC dengan baik:

Model (18 model) menangani semua data dan relasi database
View (41 React components + 4 Blade templates) menampilkan interface kepada user
Controller (30+ controllers) menghubungkan Model dan View
Ditambah dengan layer tambahan:

Services: Memisahkan logika bisnis dari Controller
Middleware: Filtering request (autentikasi, otorisasi)
Routes: Mendefinisikan endpoint aplikasi
Imports/Exports: Menangani data Excel
Struktur ini membuat kode:

✅ Terorganisir: Setiap komponen punya tanggung jawab jelas
✅ Mudah dipelihara: Perubahan di satu layer tidak mempengaruhi layer lain
✅ Scalable: Mudah menambah fitur baru
✅ Testable: Setiap layer bisa ditest secara terpisah
Dibuat untuk keperluan UAS - Penjelasan Arsitektur MVC Projek OSCE

Semoga bermanfaat! 🎓