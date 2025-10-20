# Proyek Pengembangan Aplikasi LSP POLINES

Selamat datang di repository proyek Pengembangan Perangkat Lunak untuk LSP Polines. Dokumen ini berisi semua informasi penting yang dibutuhkan oleh tim untuk memulai dan berkontribusi pada proyek.

---

## 🚀 Deskripsi Proyek

Proyek ini bertujuan untuk merancang dan membangun Sistem Informasi Lembaga Sertifikasi Profesi (LSP) Politeknik Negeri Semarang. Sistem ini dirancang untuk mendigitalisasi dan mengoptimalkan seluruh alur kerja proses sertifikasi kompetensi, mulai dari pendaftaran peserta hingga penerbitan sertifikat.

---

## 🛠️ Teknologi yang Digunakan

-   **Backend:** Laravel 12
-   **Frontend:** Blade, Tailwind CSS, Alpine.js
-   **Database:** MySQL / MariaDB
-   **Server Lokal:** XAMPP / Laragon
-   **Build Tool:** Vite

---

## ⚙️ Panduan Instalasi Lokal

Ikuti langkah-langkah ini agar proyek bisa berjalan di komputermu.

1.  **Clone Repository**

    -   Buka folder xampp/htdocs/
    -   Buka terminal

    ```bash
    git clone https://github.com/trktic24/PBL_LSP.git
    cd PBL_LSP
    ```

2.  **Install Dependencies**

    ```bash
    # Copy file environment
    cp .env.example .env

    # Install dependency PHP (Composer)
    composer install

    # Install dependency JavaScript (NPM)
    npm install
    ```

3.  **Konfigurasi Environment**

    -   Buka file `.env`.
    -   Buat database baru di phpMyAdmin (misal: `pbl_lsp`).
    -   Sesuaikan konfigurasi database di file `.env`:
        ```
        DB_CONNECTION=mysql
        DB_DATABASE=pbl_lsp
        DB_USERNAME=root
        DB_PASSWORD=
        ```

4.  **Setup Aplikasi**

    ```bash
    # Generate application key
    php artisan key:generate

    # Jalankan migrasi untuk membuat tabel database
    php artisan migrate
    ```

5.  **Jalankan Proyek**
    -   Buka satu terminal dan jalankan Vite:
        ```bash
        npm run dev
        ```
    -   Buka terminal **BARU** dan jalankan server Laravel:
        ```bash
        php artisan serve
        ```
    -   Buka browser dan akses: `http://127.0.0.1:8000`

---

## 🌊 Alur Kerja & Aturan Git (Workflow)

**PENTING!** Ikuti alur ini agar tidak terjadi kekacauan.

1.  **Selalu Mulai dari `dev`:** Sebelum mulai coding, pastikan branch `dev` di komputermu adalah yang terbaru.

    ```bash
    git checkout dev
    git pull origin dev
    ```

2.  **Pindah ke Branch Kelompokmu:**

    ```bash
    git checkout [nama-branch-kelompokmu]
    # Contoh: git checkout Kelompok_1
    # Contoh: git checkout Kelompok_2
    # Contoh: git checkout Kelompok_3
    # Contoh: git checkout Kelompok_4
    ```

3.  **Coding & Commit:** Lakukan pekerjaanmu di branch kelompok. Buat commit secara berkala dengan pesan yang jelas.

    ```bash
    git add .
    git commit -m "Feat: Membuat halaman dashboard admin"
    ```

4.  **Push ke Branch Kelompok:** Kirim pekerjaanmu ke repository GitHub.

    ```bash
    git push origin [nama-branch-kelompokmu]
    ```

5.  **Buat Pull Request (PR):** Jika fiturnya sudah selesai, buka GitHub dan buat **Pull Request dari `branch-kelompokmu` ke branch `dev`**. Beri judul dan deskripsi yang jelas, lalu mention leadernya untuk review.

**Aturan Emas:**

-   **DILARANG** push langsung ke `main` atau `dev`.
-   Semua perubahan **WAJIB** melalui Pull Request.
-   Selalu `git pull origin dev` sebelum memulai pekerjaan baru.

## 📂 Struktur Folder Utama

Berikut panduan struktur folder yang sering digunakan selama pengembangan proyek:

```
.
├── 📂 app/                  # FOLDER UTAMA: Inti & logika aplikasi
│   ├── 📂 Http/
│   │   ├── 📂 Controllers/   # ↳ Logika untuk setiap halaman
│   │   └── 📂 Middleware/    # ↳ Filter keamanan (e.g., login check)
│   └── 📂 Models/            # ↳ Penghubung ke tabel database
│
├── 📂 database/              # FOLDER UTAMA: Semua tentang database
│   └── 📂 migrations/        # ↳ Desain / "cetak biru" tabel database
│
├── 📂 public/                # ↳ Aset yang bisa diakses publik (gambar, css jadi)
│
├── 📂 resources/             # FOLDER UTAMA: Bahan mentah untuk frontend
│   ├── 📂 css/               # ↳ Kode sumber Tailwind CSS
│   ├── 📂 js/                # ↳ Kode sumber JavaScript / Alpine.js
│   └── 📂 views/             # ↳ File tampilan UI (.blade.php)
│
├── 📂 routes/                # FOLDER UTAMA: Peta URL aplikasi
│   └── 📜 web.php            # ↳ Daftar semua URL halaman web
│
├── 📜 .env                   # ↳ Konfigurasi database & environment (RAHASIA)
├── 📜 composer.json          # ↳ Daftar package PHP (backend)
└── 📜 package.json           # ↳ Daftar package JS (frontend)
```

## 🤝 Panduan Kontribusi

-   Selalu pastikan kode udah jalan lokal sebelum push
-   Gunakan bahasa commit yang konsisten (Feat:, Fix:, Refactor:)
-   Jangan ubah file `.env`, `.gitignore`, atau `composer.json` tanpa izin leader

## 👨‍💻 Pembagian Tim

| Branch       | Penanggung Jawab | Anggota                               |
| ------------ | ---------------- | ------------------------------------- |
| `Kelompok_1` | Ghufron Ainun    | Abyan, Dimaz, Annisa, Riztika, Ifa    |
| `Kelompok_2` | Paulus Ale       | Cezar, Bagas, Baim, Devi, Nabila      |
| `Kelompok_3` | Rajaba Hamim     | Zalfa, Diah, Fariz, Izza, Oksa        |
| `Kelompok_4` | Zulfikri Arya    | Roihan, Terra, Dimas Adhie, Sri, Rafa |
