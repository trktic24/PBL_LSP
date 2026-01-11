# Pull Request: Implementasi FR.AK.07 & Manajemen Template

## Ringkasan Perubahan
PR ini menambahkan fitur konfigurasi dan manajemen template untuk formulir **FR.AK.07 (Ceklis Penyesuaian Wajar dan Beralasan)**. Admin sekarang dapat mengaktifkan formulir ini pada Skema Sertifikasi dan mengatur nilai default untuk kolom isian utama, yang akan otomatis muncul saat Asesor mengisi formulir.

## Fitur Utama
1.  **Konfigurasi Skema**: Admin dapat mengaktifkan/menonaktifkan FR.AK.07 pada menu Detail Skema.
2.  **Manajemen Template**: Admin dapat mengatur **Nilai Default** untuk:
    *   Acuan Pembanding Asesmen
    *   Metode Asesmen
    *   Instrumen Asesmen
3.  **Auto-Fill Asesor**: Saat Asesor membuka form FR.AK.07 baru, kolom-kolom tersebut akan otomatis terisi sesuai template yang diatur Admin (jika belum ada data).

## Detail Teknis

### 1. Database Migration
Menambahkan kolom `fr_ak_07` pada tabel `list_form` untuk menyimpan status aktif/non-aktif formulir per skema.

| File Migrasi | Deskripsi |
| :--- | :--- |
| `2026_01_11_153851_add_fr_ak_07_to_list_form_table.php` | Menambahkan kolom `fr_ak_07` (boolean) ke tabel `list_form`. |

### 2. Perubahan Kode

#### Backend
- **Model `ListForm`**: Menambahkan `fr_ak_07` ke `$fillable`.
- **Controller `Admin\DetailSkemaController`**:
    - Menambahkan `FR.AK.07` ke konfigurasi `$formConfig`.
    - Menambahkan mapping route untuk tombol "Kelola" (Template) dan "Lihat" (List Asesi).
- **Controller `FrAk07Controller`**:
    - `editTemplate()`: Logic untuk load view editor template.
    - `storeTemplate()`: Logic untuk menyimpan nilai default ke tabel `master_form_templates`.
    - `create()` (Frontend): Logic untuk memuat `defaultValues` dari template dan mengirimnya ke view form.

#### Frontend / View
- **`resources/views/Admin/master/skema/template/ak07.blade.php`** (BARU): Interface untuk Admin mengubah nilai default template.
- **`resources/views/frontend/AK_07/FR_AK_07.blade.php`**: Update form untuk menampilkan nilai default jika data database masih kosong.

## Checkpoint Verifikasi
- [ ] Buka **Admin > Master Skema > Detail Skema**.
- [ ] Pastikan ada baris **FR.AK.07 - Ceklis Penyesuaian Wajar dan Beralasan**.
- [ ] Klik tombol **Kelola**, pastikan bisa menyimpan nilai default (Acuan, Metode, Instrumen).
- [ ] Login sebagai **Asesor**, buka jadwal asesmen, pilih menu FR.AK.07.
- [ ] Pastikan form otomatis terisi dengan nilai default yang diset oleh Admin.

### 1. Migrasi & Database
| File | Aksi | Alasan |
| :--- | :--- | :--- |
| `2026_01_11_..._create_master_form_templates_table.php` | **New** | Membuat tabel yang hilang di prod. Menggunakan `Schema::hasTable` safety check. **Updated:** Menggunakan *explicit index name* untuk kompatibilitas dropUnique. |
| `2026_01_11_..._add_id_jadwal_...` | **New** | Menambah kolom isolasi. |
| `master_skema.php` | **Revert** | Menghapus definisi tabel ganda untuk kebersihan kode. |

### 2. Code Fixes
- **Models**: Rename `ResponPotensiAk07` -> `ResponPotensiAK07` (Fix Case Sensitive).
- **Controllers**: Ganti `ResponApl2Ia01` -> `ResponApl02Ia01` (Fix Typo).
- **Admin TUK**: Tambah preview Maps & validasi regex embed link.

### 3. Assessment Tracker Refactoring
- **Feature**: Sinkronisasi layout & logika `Detail Asesmen` (Admin) dengan `Tracker` (Asesor).
- **Changes**:
    - **Controller**: Eager load semua relasi IA (`ia01`, `ia03`, `ia04`, `ia08`, `ia09`, `ia11`) di `AsesorProfileController`.
    - **Model**: Menambahkan relasi `ia03` dan `ia11` pada `DataSertifikasiAsesi` yang sebelumnya referensi methodnya tidak ada (fix Internal Server Error).
    - **View**: Update `asesor_assessment_detail.blade.php` untuk menampilkan semua item IA (01-11) dengan logika status yang konsisten.

### 4. Admin Asesor UI Enhancements
-   **Admin Asesi List**: Updated Tracker link, added links to Schema and Schedule details.
-   **View Refactor**: Separated "Skema" and "Jadwal" into distinct columns in Tinjauan and Tracker views.
-   **UX**: Added time range to Schedule column.

### 📋 Checklist Verifikasi
- [x] **Hotfix Valid**: Tabel `master_form_templates` berhasil dibuat di lokal (fresh & existing).
- [x] **Linux Ready**: Tidak ada error *Class not found*.
- [x] **Features**: Isolasi template berjalan, TUK map preview muncul.
- [x] **Tracker Sync**: Admin bisa melihat status semua form IA (01-11) sesuai kondisi asesor.
- [x] **UI/UX**: Check Admin Asesi List links and separated columns in Tinjauan/Tracker.

## Catatan Deployment
- Jalankan migrasi baru:
  ```bash
  php artisan migrate
  ```
