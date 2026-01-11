# Pull Request: Refactor Database, Isolasi Templat, & Hotfix Migrasi

## 🏗 Ringkasan Perubahan
PR ini mencakup stabilisasi sistem di Linux, fitur isolasi templat, dan **HOTFIX** untuk mengatasi error *missing table* di server production.

### 🎯 Poin Utama
1.  **HOTFIX Database**: Memisahkan pembuatan tabel `master_form_templates` ke migrasi baru agar terdeteksi di server production.
2.  **Isolasi Templat**: Penambahan kolom `id_jadwal` agar perubahan template tidak merusak data historis.
3.  **Linux Compatibility**: Renaming model `AK07` ke PascalCase untuk mencegah error *Class Not Found*.
4.  **Admin Fix**: Perbaikan akses 403 pada view form dan typo relasi di controller.
5.  **TUK Admin**: Validasi ketat & preview link embed Google Maps.

---

## ⚠️ PANDUAN DEPLOYMENT (PRODUCTION)

> **Masalah**: Server production mengalami error `Table 'master_form_templates' doesn't exist` karena migrasi sebelumnya ter-skip.
> **Solusi**: PR ini membawa migrasi baru yang **WAJIB** dijalankan.

### Langkah Demi Langkah
1.  **Backup Database** (Sangat disarankan).
2.  **Pull Code Terbaru**:
    ```bash
    git pull origin fix/migrasi_for_server
    ```
3.  **Jalankan Migrasi**:
    ```bash
    php artisan migrate
    ```
    *Sistem akan otomatis mendeteksi dan membuat tabel `master_form_templates` serta menambahkan kolom-kolom baru.*

4.  **Verifikasi**:
    Cek halaman yang sebelumnya error: `https://pbl250107.informatikapolines.id/admin/skema/2/template/mapa01/62`.
    Pastikan halaman sudah bisa diakses (status 200 OK).

---

## 📂 Detail Teknis (Untuk Reviewer)

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

### 📋 Checklist Verifikasi
- [x] **Hotfix Valid**: Tabel `master_form_templates` berhasil dibuat di lokal (fresh & existing).
- [x] **Linux Ready**: Tidak ada error *Class not found*.
- [x] **Features**: Isolasi template berjalan, TUK map preview muncul.
