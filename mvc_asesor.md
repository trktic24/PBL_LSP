# MVC Component: Manajemen Asesor (Master & Profile)

Dokumen ini menjelaskan implementasi pola MVC (Model-View-Controller) pada fitur **Manajemen Asesor** dalam aplikasi LSP. Fitur ini mencakup pengelolaan data master asesor (CRUD dengan Wizard) dan pengelolaan profil asesor (Verifikasi Bukti, Timeline, dan Detail Asesmen).

---

## 📚 Daftar Isi
1. [Pengenalan MVC](#pengenalan-mvc)
2. [Komponen Model](#komponen-model)
3. [Komponen View](#komponen-view)
4. [Komponen Controller](#komponen-controller)
5. [Layer Tambahan dalam Projek](#layer-tambahan-dalam-projek)
6. [Alur Kerja MVC dengan Contoh](#alur-kerja-mvc-dengan-contoh)
7. [Struktur Direktori Lengkap](#struktur-direktori-lengkap)
8. [📊 Rangkuman Perbandingan Komponen MVC](#-rangkuman-perbandingan-komponen-mvc)
9. [🎓 Kesimpulan](#-kesimpulan)

---

## Pengenalan MVC

**MVC (Model-View-Controller)** adalah pola arsitektur yang digunakan dalam modul Manajemen Asesor ini untuk memisahkan logic aplikasi menjadi 3 komponen utama:

*   **Model**: Menangani struktur data asesor, validasi logika database, dan relasi data (misal: relasi Asesor ke User atau Skema).
*   **View**: Menangani tampilan antarmuka (UI) kepada Admin, seperti form wizard pendaftaran, tabel daftar asesor, dan dashboard profil.
*   **Controller**: Menangani alur logika, memproses input dari View (seperti upload file atau simpan data), dan berinteraksi dengan Model sebelum mengembalikan respon ke View.

Penerapan MVC membuat pengelolaan code fitur Asesor menjadi lebih terstruktur, terutama karena fitur ini memiliki flow yang cukup kompleks (Wizard Step, Upload Dokumen, Verifikasi).

---

## Komponen Model

### 📍 Lokasi
`app/Models/Asesor.php`

### 🎯 Fungsi Utama
Model `Asesor` bertugas sebagai representasi tabel `asesor` di database. Ia mengatur bagaimana data diambil, disimpan, dan diubah.

### Triggers & Scope
Dalam modul ini, Model digunakan untuk:
1.  **Mass Assignment**: Mendefinisikan kolom mana saja yang aman untuk diisi secara otomatis (`$fillable`), seperti data diri dan status verifikasi.
2.  **Relasi Data**: Mengambil data terkait tanpa query SQL manual yang rumit.

### 📝 Struktur Data (Atribut)
*   **Data Diri**: `nama_lengkap`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `kebangsaan`, `pekerjaan`, `alamat_rumah`, `nomor_hp`.
*   **Data Profesi**: `nomor_regis`, `id_skema` (skema utama), `NPWP`.
*   **Data Bank**: `nama_bank`, `norek`.
*   **File Path**: `ktp`, `pas_foto`, `NPWP_foto`, `CV`, `ijazah`, `sertifikat_asesor`, `sertifikasi_kompetensi`, `tanda_tangan`.
*   **Status**: `status_verifikasi`.

### 🔗 Definisi Relasi (Eloquent)
```php
// 1. Relasi ke Akun User (1 Asesor = 1 User)
public function user() {
    return $this->belongsTo(User::class, 'id_user', 'id_user');
}

// 2. Relasi ke Skema Utama (Bidang Keahlian Utama)
public function skema() {
    return $this->belongsTo(Skema::class, 'id_skema', 'id_skema');
}

// 3. Relasi ke Banyak Skema (Lisensi Tambahan) - Many to Many
public function skemas() {
    return $this->belongsToMany(Skema::class, 'Transaksi_asesor_skema', 'id_asesor', 'id_skema');
}

// 4. Relasi ke Riwayat Jadwal - One to Many
public function jadwals() {
    return $this->hasMany(Jadwal::class, 'id_asesor', 'id_asesor');
}
```

---

## Komponen View

### 📍 Lokasi
*   `resources/views/Admin/master/asesor/` (Modul Master)
*   `resources/views/Admin/profile_asesor/` (Modul Profil)

### 🎯 Fungsi Utama
View bertugas menampilkan data yang dikirim oleh Controller dan menyediakan form interaktif bagi pengguna. Menggunakan **Blade Templating Engine**.

### 📂 Daftar File View

**A. Sub-Modul Master Asesor (CRUD & Wizard)**
| Nama File | Fungsi |
| :--- | :--- |
| `master_asesor.blade.php` | Halaman index utama. Menampilkan tabel daftar asesor, filter (Skema, Status), dan pagination. |
| `add_asesor1.blade.php` | **Wizard Step 1**: Form registrasi akun (Email & Password). |
| `add_asesor2.blade.php` | **Wizard Step 2**: Form data diri, alamat, dan pemilihan Skema. |
| `add_asesor3.blade.php` | **Wizard Step 3**: Form upload dokumen kelengkapan (KTP, Ijazah, CV). |
| `edit_asesor{1-3}.blade.php` | Versi edit dari wizard di atas (data sudah terisi). |

**B. Sub-Modul Profile Asesor (Monitoring & Verifikasi)**
| Nama File | Fungsi |
| :--- | :--- |
| `asesor_profile_settings.blade.php` | Tab **Profil**: Melihat detail data diri asesor secara read-only. |
| `asesor_profile_bukti.blade.php` | Tab **Bukti**: Menampilkan galeri dokumen asesor untuk diverifikasi Admin (Approve/Reject). |
| `asesor_profile_tinjauan.blade.php` | Tab **Tinjauan**: List riwayat jadwal asesmen yang pernah diampu asesor. |
| `asesor_profile_tracker_skema.blade.php` | **Timeline View**: Visualisasi progres asesmen (Penetapan -> Asesmen -> Laporan). |
| `daftar_asesi.blade.php` | Menampilkan tabel asesi dalam satu jadwal spesifik yang dinilai asesor tersebut. |

---

## Komponen Controller

### 📍 Lokasi
`app/Http/Controllers/Admin/`

### 🎯 Fungsi Utama
Controller bertugas sebagai "otak" yang menghubungkan Model data dengan View tampilan. Dalam modul ini, Controller dipisah menjadi dua fokus area.

### 1. AsesorController.php (Manajemen Master)
Fokus pada manipulasi data dasar (CRUD).
*   `index()`: Mengambil data asesor dengan eager loading (`with(['skema', ...])`), melakukan filtering pencarian, dan mengirim data ke `master_asesor.blade.php`.
*   `store()`: Menangani logika penyimpanan final dari Wizard. Menggunakan **Database Transaction** untuk memastikan data User, Asesor, dan File tersimpan bersamaan atau batal semua jika gagal.
*   `destroy()`: Menghapus data asesor secara *cascade* (hapus file fisik -> hapus data asesor -> hapus user login). Validasi: Tidak boleh hapus jika punya jadwal aktif.

### 2. AsesorProfileController.php (Manajemen Profil & Aktivitas)
Fokus pada monitoring dan detail operasional.
*   `showProfile()`, `showBukti()`, `showTinjauan()`: Method-method untuk merender tab-tab pada halaman profil.
*   `verifyDocument()`: Endpoint **AJAX** untuk melakukan verifikasi dokumen asesi secara cepat tanpa refresh halaman.
*   `storeBukti()`, `deleteBukti()`: Menangani upload file dokumen tambahan secara asinkronus (AJAX) di halaman profil.

---

## Layer Tambahan dalam Projek

Selain MVC standar, modul ini menggunakan beberapa layer tambahan untuk mendukung fungsionalitas:

### 1. 🛣️ Routes (Routing)
File: `routes/web.php`
Mendefinisikan URL yang bisa diakses user. Menggunakan middleware **Admin** untuk keamanan.

**Kelompok Route Utama:**
*   **Wizard Data**: `/admin/asesor/add/step{1-3}` -> Menangani flow pendaftaran bertahap.
*   **Profil Detail**: `/admin/asesor/profile/{id}/*` -> Menangani akses sub-menu profil (bukti, tinjauan).
*   **AJAX Endpoint**: `/admin/asesor/{id}/document/verify` -> API internal untuk verifikasi dokumen.

### 2. 🚦 Middleware
*   `auth`: Memastikan user sudah login.
*   `role:admin`: Memastikan hanya user dengan role Admin yang bisa mengakses manajemen data asesor ini.

### 3. 💾 Session & Storage
*   **Session**: Digunakan pada `AsesorController` untuk menyimpan data sementara antar langkah Wizard (Step 1 -> Step 2 -> Step 3) sebelum disimpan ke database.
*   **Storage Facade**: Digunakan untuk menangani file upload ke folder `private_docs/asesor_docs/{id_user}`.

---

## Alur Kerja MVC dengan Contoh

**Kasus: Admin Membuka Halaman Profil Bukti Asesor**

1.  **Request**: User mengakses URL `/admin/asesor/profile/10/bukti`.
2.  **Route**: `routes/web.php` mencocokkan URL ke `AsesorProfileController@showBukti`.
3.  **Middleware**: Cek apakah user login & admin? (Lolos).
4.  **Controller**:
    *   `AsesorProfileController` memanggil **Model** `Asesor`.
    *   `Asesor::findOrFail(10)` -> Query ke Database mencari asesor ID 10.
5.  **Model**: Mengembalikan objek data Asesor (termasuk path file foto, ktp, dll).
6.  **Controller**:
    *   Menyiapkan array struktur dokumen (`$documents`) untuk looping di View.
    *   Mengirim data ke **View** `Admin.profile_asesor.asesor_profile_bukti`.
7.  **View**:
    *   `asesor_profile_bukti.blade.php` merender HTML.
    *   Menggunakan looping `@foreach` untuk menampilkan kartu preview setiap dokumen.
8.  **Response**: Browser menampilkan halaman galeri bukti kelengkapan asesor.

---

## Struktur Direktori Lengkap

Berikut adalah lokasi file-file yang terlibat dalam modul Manajemen Asesor:

```
/opt/lampp/htdocs/PBL_LSP/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Admin/
│   │   │       ├── AsesorController.php         [Master CRUD]
│   │   │       └── AsesorProfileController.php  [Detail & Verifikasi]
│   │   └── Middleware/
│   │       └── RoleMiddleware.php               [Proteksi Akses]
│   └── Models/
│       └── Asesor.php                           [Model Data]
├── resources/
│   └── views/
│       └── Admin/
│           ├── master/
│           │   └── asesor/                      [View Master]
│           │       ├── master_asesor.blade.php
│           │       ├── add_asesor{1-3}.blade.php
│           │       └── edit_asesor{1-3}.blade.php
│           └── profile_asesor/                  [View Profile]
│               ├── asesor_profile_settings.blade.php
│               ├── asesor_profile_bukti.blade.php
│               ├── asesor_profile_tinjauan.blade.php
│               ├── asesor_profile_tracker.blade.php
│               └── daftar_asesi.blade.php
└── routes/
    └── web.php                                  [Definisi URL]
```

---

## 📊 Rangkuman Perbandingan Komponen MVC

| Aspek | Model | View | Controller |
| :--- | :--- | :--- | :--- |
| **Tanggung Jawab** | Mengelola data & database | Menampilkan UI | Menghubungkan Model & View |
| **Lokasi** | `app/Models/` | `resources/views/Admin/` | `app/Http/Controllers/Admin/` |
| **Teknologi** | Eloquent ORM (PHP) | Blade Template + Tailwind CSS + Alpine.js | PHP (Laravel) |
| **Contoh File** | `Asesor.php` | `master_asesor.blade.php` | `AsesorController.php` |
| **Interaksi** | Database ↔ Model | View → User | Request → Controller → Response |
| **Logika** | Relasi data, casting, scope | Presentasi data, form wizard | Orchestration, validasi, transaction |

---

## 🎓 Kesimpulan

Implementasi modul **Manajemen Asesor** ini mengikuti standar MVC yang baik dengan pemisahan tugas yang jelas:

1.  Logic bisnis yang kompleks (seperti Wizard Pendaftaran) diisolasi di **Controller** (`AsesorController`).
2.  Logic tampilan (termasuk visualisasi Timeline dan dokumen) dipisah ke dalam banyak file **View** yang spesifik (modular) di folder `Admin/profile_asesor` dan `Admin/master/asesor`.
3.  **Model** `Asesor` menjadi pusat data tunggal yang menghubungkan data asesor dengan sistem lain (Jadwal, Skema, User).

Struktur ini memudahkan *maintenance*, misalnya jika ingin mengubah tampilan form pendaftaran, cukup edit file View tanpa mengganggu logika penyimpanan di Controller.
