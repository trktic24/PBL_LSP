# 📝 Kisi-Kisi Teknis: Modul Manajemen Asesor

Dokumen ini adalah ringkasan teknis untuk "Kisi-kisi" berdasarkan implementasi MVC pada fitur **Manajemen Asesor**. Fokus pada file-file yang telah dikerjakan.

---

## 3. Mengenai Database

Fokus pada bagaimana data disimpan dan berelasi menggunakan **Model Eloquent**.

*   **Tabel Utama**: `asesor`
*   **Model File**: `app/Models/Asesor.php`
*   **Primary Key**: `id_asesor`
*   **Relasi Penting**:
    1.  `checklist` **User (Akun)**: Relasi `belongsTo` ke model `User` (`id_user`). Setiap asesor wajib punya 1 akun login.
    2.  `checklist` **Skema Utama**: Relasi `belongsTo` ke model `Skema` (`id_skema`). Bidang keahlian utama asesor.
    3.  `checklist` **Skema Lisensi (Pivot)**: Relasi `belongsToMany` ke `Skema` via tabel pivot `transaksi_asesor_skema`. Untuk asesor yang punya banyak lisensi keahlian.
    4.  `checklist` **Riwayat Jadwal**: Relasi `hasMany` ke model `Jadwal`. Mengecek apakah asesor sedang bertugas (untuk validasi sebelum hapus).
*   **Fitur Eloquent yang Dipakai**:
    *   **Mass Assignment (`$fillable`)**: Menentukan kolom aman (Nama, NIK, No. HP, File Path) untuk diisi sekaligus.
    *   **Eager Loading**: Menggunakan `with(['skema', 'user'])` di Controller untuk mencegah N+1 Query problem saat menampilkan list.

---

## 4. Mengenai Frontend (View)

Fokus pada tampilan antarmuka (UI) dan interaksi pengguna.

*   **Teknologi Utama**: Laravel Blade, Tailwind CSS, Alpine.js (untuk interaksi ringan seperti dropdown filter).
*   **Lokasi File View**: `resources/views/Admin/`
*   **Implementasi Fitur UI**:
    1.  **Wizard Pendaftaran (Multistep Form)**:
        *   File: `add_asesor1.blade.php` (Akun), `add_asesor2.blade.php` (Data Diri), `add_asesor3.blade.php` (Dokumen).
        *   **Konsep**: Memecah form panjang menjadi 3 halaman agar user tidak bosan. Data dibawa antar halaman via Backend Session.
    2.  **Dashboard Profil (Tab Menu)**:
        *   File: `asesor_profile_*.blade.php`
        *   **Konsep**: Menggunakan Navigasi Tab (General, Bukti, Tinjauan) untuk mengorganisir informasi detail asesor yang banyak.
    3.  **Tabel & Filter**:
        *   File: `master_asesor.blade.php`
        *   Fitur: Pencarian realtime (nama/email), Filter Dropdown (berdasarkan Skema & Status Verifikasi), dan Pagination.
    4.  **Preview Dokumen**:
        *   File: `asesor_profile_bukti.blade.php`
        *   Fitur: Menampilkan thumbnail/link file bukti (KTP, CV) dan status verifikasinya (Approved/Rejected).

---

## 5. Mengenai Backend (Controller)

Fokus pada logika bisnis, alur data, dan pemrosesan request.

*   **Logic Master Data**: `Admin\AsesorController.php`
    *   **Session State**: Menggunakan `session()->put('asesor', $data)` untuk menyimpan data sementara saat user pindah dari Step 1 ke Step 2 wizard pendaftaran.
    *   **Database Transaction (`DB::beginTransaction`)**: Penting! Digunakan saat `store()` akhir. Data User, Data Asesor, dan File Upload disimpan dalam satu paket. Jika satu gagal, semua batal.
    *   **Validasi Input**: Menggunakan `$request->validate()` di setiap step wizard untuk memastikan data valid (misal: email unik, file max 2MB).
    *   **Cascade Delete**: Menghapus data secara berurutan: Hapus File Fisik -> Hapus Data Asesor -> Hapus Akun User.

*   **Logic Operasional**: `Admin\AsesorProfileController.php`
    *   **Pemisahan Tugas**: Controller ini dipisah dari master agar logic tidak menumpuk. Khusus menangani detail profil.
    *   **AJAX Processing**: Endpoint `verifyDocument` dan `storeBukti` menerima request JSON tanpa reload halaman, membuat UX verifikasi lebih cepat (sat-set).
    *   **File Handling**: Menggunakan `Storage::disk('private_docs')` untuk menyimpan dokumen sensitif (KTP, Ijazah) di folder privat, bukan public.

---
*Catatan ini disusun spesifik berdasarkan kode yang ada di `mvc_asesor.md`.*
