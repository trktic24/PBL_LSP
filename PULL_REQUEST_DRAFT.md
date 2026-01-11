# Pull Request: Refactor Database, Isolasi Templat, & Fix Case‑Sensitivity (Linux)

## 🏗 Ringkasan Perubahan  
PR ini mencakup perbaikan kritikal untuk stabilitas sistem di Linux, penambahan isolasi templat soal per jadwal, dan konsolidasi migrasi.

### 🎯 Fitur Utama & Fix
- **HOTFIX: Missing Table** – Menambahkan migrasi dedikasi untuk `master_form_templates` guna mengatasi error "Table 'master_form_templates' doesn't exist" pada production.
- **Isolasi Templat Soal** – Penambahan kolom `id_jadwal` pada tabel master dan soal.  
- **Fix Case‑Sensitivity (Linux)** – Renaming model‑model AK‑07 ke PascalCase.  
- **Fix Relasi Database** – Typo `ResponApl2Ia01` → `ResponApl02Ia01` di controller.  
- **Admin Read‑Only Access** – Admin dapat melihat form asesmen tanpa blokir 403.  
- **Konsolidasi Migrasi** – Menggabungkan migrasi terfragmentasi menjadi satu definisi tabel.  

## ⚠️ Panduan Teknis Deployment (Penting)  
**Baca sebelum merge/deploy ke production**

### Skenario 1: Deploy ke Server Baru (Fresh Install)  
✅ Aman. Jalankan:  
```bash
php artisan migrate --seed
```

### Skenario 2: Update ke Server Existing (Production)  
⛔️ **Jangan** jalankan `php artisan migrate` secara langsung!

**Langkah‑langkah aman:**  
1. **Backup database** terlebih dahulu.  
2. **Identifikasi migrasi baru** – File berikut **WAJIB** dijalankan (aditif & fix):  
   - `2026_01_11_062900_create_master_form_templates_table.php` (Fix missing table)
   - `2026_01_11_062913_add_id_jadwal_to_master_form_templates.php`  
   - `2026_01_11_065646_add_id_jadwal_to_soal_ia05_and_ia06.php`  

3. **Handling migrasi konsolidasi** – Tandai migrasi lama yang sudah dijalankan secara manual di tabel `migrations` (jika belum ada):  
   ```sql
   INSERT INTO migrations (migration, batch) VALUES ('2025_10_23_041911_master_skema', 99);
   INSERT INTO migrations (migration, batch) VALUES ('2025_10_30_124822_data_sertifikasi_asesi', 99);
   -- tambahkan entri lain bila diperlukan
   ```  
4. **Jalankan migrasi aditif**:  
   ```bash
   php artisan migrate
   ```

## 📂 Detail Perubahan File  

### Models (Fix Case‑Sensitivity & Relations)  
| Status | File | Keterangan |
|:---|:---|:---|
| **RENAME** | `ResponPotensiAk07.php` → `ResponPotensiAK07.php` | Fix Linux case‑sensitivity |
| **RENAME** | `PoinPotensiAK07.php` | Fix Linux case‑sensitivity |
| **RENAME** | `ResponDiperlukanPenyesuaianAK07.php` | Fix Linux case‑sensitivity |
| **DELETE** | `ResponApl2Ia01.php` | Dihapus, diganti `ResponApl02Ia01` |
| **UPDATE** | `DataSertifikasiAsesi.php` | Perbaikan relasi `responApl02Ia01` |

### Controllers (Fix Imports & Logic)  
- `PraasesmenController.php` – perbaikan import & typo `ResponApl2Ia01` → `ResponApl02Ia01`.  
- `AsesiController.php` – perbaikan string relation di eager loading.  
- `Ak03Controller.php` – perbaikan string relation di eager loading.  
- `FrAk07Controller.php` – update import model AK‑07 yang baru.  

### Database Migrations  

#### 1️⃣ Hotfix & Fitur Baru (Wajib Jalan)
- `2026_01_11_062900_create_master_form_templates_table.php` – Membuat tabel `master_form_templates` (dipisah dari `master_skema` agar jalan di prod).
- `add_id_jadwal_to_master_form_templates.php` – menambahkan kolom `id_jadwal`.
- `add_id_jadwal_to_soal_ia05_and_ia06.php` – menambahkan kolom `id_jadwal`.

#### 2️⃣ Konsolidasi (Cleanup)  
- `master_skema.php` – Dihapus bagian `master_form_templates` (dikembalikan ke original `skema` only).
- `data_sertifikasi_asesi.php` – mencakup kolom `rekomendasi_ak01`.
- `create_struktur_organisasis_table.php` – mencakup kolom `urutan`.

#### 3️⃣ Bugfix (Perbaikan Struktur)  
- `ia03.php` – menambahkan kolom `pertanyaan`, `jawaban`, `tanggapan`.
- `ia10.php` – mengubah kolom profil menjadi `nullable`.
- `pertanyaan_ia10.php` – memperbaiki default value boolean.

#### 📋 Ringkasan Migrasi  
| Tipe | File | Perubahan Teknis |
|:---|:---|:---|
| **Revert** | `2025_10_23_041911_master_skema.php` | Hapus kembali definisi `master_form_templates` |
| **HOTFIX** | `2026_01_11_062900_create_master_form_templates_table.php` | Buat tabel `master_form_templates` (New File) |
| **Cleanup** | `2025_10_30_124822_data_sertifikasi_asesi.php` | Merge kolom `rekomendasi_ak01` |
| **Fix** | `2025_11_03_110931_ia03.php` | Tambah kolom jawaban missing |
| **Fix** | `2025_11_04_075032_ia10.php` | Set kolom profil ke `nullable` |
| **Fix** | `2025_11_04_075126_pertanyaan_ia10.php` | Fix default value boolean |
| **Cleanup** | `2025_11_18_171157_create_struktur_organisasis_table.php` | Merge kolom `urutan` |
| **New (Safe)** | `2026_01_11_062913_add_id_jadwal_to_master_form_templates.php` | Tambah kolom `id_jadwal` |
| **New (Safe)** | `2026_01_11_065646_add_id_jadwal_to_soal_ia05_and_ia06.php` | Tambah kolom `id_jadwal` |

## 📦 CLI Commands for Migration Handling  

```bash
# Masuk ke MySQL
mysql -u <USER> -p<PASSWORD> <DB_NAME>

# Tandai migrasi konsolidasi sebagai selesai
INSERT INTO migrations (migration, batch) VALUES ('2025_10_23_041911_master_skema', 99);
INSERT INTO migrations (migration, batch) VALUES ('2025_10_30_124822_data_sertifikasi_asesi', 99);
INSERT INTO migrations (migration, batch) VALUES ('2025_11_03_110931_ia03', 99);
INSERT INTO migrations (migration, batch) VALUES ('2025_11_04_075032_ia10', 99);
INSERT INTO migrations (migration, batch) VALUES ('2025_11_04_075126_pertanyaan_ia10', 99);
INSERT INTO migrations (migration, batch) VALUES ('2025_11_18_171157_create_struktur_organisasis_table', 99);
INSERT INTO migrations (migration, batch) VALUES ('2026_01_03_020243_add_rekomendasi_ak01_to_data_sertifikasi_asesi_table', 99);
INSERT INTO migrations (migration, batch) VALUES ('2026_01_07_182656_add_urutan_to_struktur_organisasis_table', 99);
INSERT INTO migrations (migration, batch) VALUES ('2026_01_09_200053_add_umpan_balik_to_lembar_jawab_ia05_table', 99);
-- Hotfix & fitur baru akan jalan otomatis via artisan migrate
```

Setelah menandai, jalankan:

```bash
php artisan migrate
```

**Pastikan backup database** sebelum mengubah tabel `migrations`.

## ✅ Checklist Verifikasi  
- [x] Import Fix – controller tidak lagi memanggil `ResponApl2Ia01`.  
- [x] Linux Ready – model AK‑07 berhasil di‑load.  
- [x] Hotfix Table – `master_form_templates` dibuat via migrasi baru `2026_01_11_062900...`.
- [ ] Migrasi Prod – pastikan prosedur *Skenario 2* dijalankan.  

---  

### Catatan untuk Ketua Tim  
- **HOTFIX diterapkan**: Tabel `master_form_templates` dipisahkan ke migrasi baru agar terdeteksi di server production yang sudah memiliki `master_skema`.  
- **Action Required**: Pull code terbaru, lalu jalankan `php artisan migrate`. Tabel `master_form_templates` akan dibuat otomatis.
