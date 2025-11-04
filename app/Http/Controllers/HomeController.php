<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Jadwal; 
use \DateTime; // <-- PENTING: Tambahkan ini

class HomeController extends Controller
{
    /**
     * [BARU] Tempat kita menyimpan semua data dummy jadwal
     * Strukturnya DISESUAIKAN agar cocok dengan detail_jadwal.blade.php
     */
    private function getSemuaDummyJadwal()
    {
        return [
            (object) [
                'id' => 1,
                'nama_skema' => 'Network Engineering',
                'tanggal' => new DateTime('2025-12-15'),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '16:00',
                'tuk' => 'Politeknik Negeri Semarang', // Diubah jadi string
                'deskripsi' => 'Deskripsi lengkap untuk skema sertifikasi Network Engineering. Peserta akan diuji kemampuannya dalam mengelola jaringan komputer skala menengah.',
                'persyaratan' => '1. Mahasiswa Aktif Polines. 2. Telah mengambil mata kuliah Jaringan Komputer. 3. Membawa laptop pribadi saat ujian.',
                'harga' => 350000,
                'tanggal_tutup' => new DateTime('2025-12-01')
            ],
            (object) [
                'id' => 2,
                'nama_skema' => 'Junior Web Developer',
                'tanggal' => new DateTime('2025-11-30'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '17:00',
                'tuk' => 'Lab RPL Gedung D4', // Diubah jadi string
                'deskripsi' => 'Deskripsi untuk Junior Web Developer. Ujian akan mencakup HTML, CSS, JavaScript, dan PHP dasar.',
                'persyaratan' => '1. Terbuka untuk umum. 2. Memahami dasar-dasar pemrograman web.',
                'harga' => 500000,
                'tanggal_tutup' => new DateTime('2025-11-20')
            ],
            (object) [
                'id' => 3,
                'nama_skema' => 'UI/UX Designer',
                'tanggal' => new DateTime('2026-01-20'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '15:00',
                'tuk' => 'Gedung Inkubator Bisnis Polines', // Diubah jadi string
                'deskripsi' => 'Uji kompetensi skema UI/UX Designer, mencakup riset pengguna, wireframing, dan prototyping menggunakan Figma.',
                'persyaratan' => '1. Membawa portofolio desain (jika ada). 2. Laptop dengan software desain (Figma/Sketch) terinstal.',
                'harga' => 450000,
                'tanggal_tutup' => new DateTime('2026-01-10')
            ]
        ];
    }

    /**
     * Menampilkan halaman home dengan data skema dan jadwal.
     */
    public function index(): View
    {
        // Kategori dummy untuk filter di carousel
        $kategori = [
            ['id' => 1, 'nama' => 'Semua'],
            ['id' => 2, 'nama' => 'Software'],
            ['id' => 3, 'nama' => 'Hardware'],
            ['id' => 4, 'nama' => 'Jaringan'],
            ['id' => 5, 'nama' => 'AI & Network'],
        ];

        // 1. Data Skema (Tidak berubah)
        $skemas = [
            ['id' => 1, 'nama' => 'Junior Web Developer', 'gambar' => 'skema1.jpg', 'kategori' => 'Software'],
            ['id' => 2, 'nama' => 'Network Administrator', 'gambar' => 'skema4.jpg', 'kategori' => 'Jaringan'],
            ['id' => 3, 'nama' => 'Database Engineer', 'gambar' => 'skema3.jpg', 'kategori' => 'Software'],
            ['id' => 4, 'nama' => 'UI/UX Designer', 'gambar' => 'skema2.jpg', 'kategori' => 'Software'],
            ['id' => 5, 'nama' => 'Cyber Security', 'gambar' => 'skema5.jpg', 'kategori' => 'Hardware'],
            ['id' => 6, 'nama' => 'Mobile Developer', 'gambar' => 'skema6.jpg', 'kategori' => 'Software'],
            ['id' => 7, 'nama' => 'Data Analyst', 'gambar' => 'skema7.jpg','kategori' => 'AI & Network'],
            ['id' => 8, 'nama' => 'Game Developer', 'gambar' => 'skema8.jpg', 'kategori' => 'Software'],
            ['id' => 9, 'nama' => 'IoT Specialist', 'gambar' => 'skema9.jpg', 'kategori' => 'Hardware'],
            ['id' => 10, 'nama' => 'Cloud Engineer', 'gambar' => 'skema10.jpg', 'kategori' => 'AI & Network'],
            ['id' => 11, 'nama' => 'AI Engineer', 'gambar' => 'skema11.jpg', 'kategori' => 'AI & Network'],
            ['id' => 12, 'nama' => 'Fullstack Developer', 'gambar' => 'skema12.jpg', 'kategori' => 'Software']
        ];

        // 2. Ambil data DUMMY Jadwal, filter, dan ambil 2
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $now = new DateTime(); 

        $jadwals = collect($semuaJadwal)
            ->filter(fn($jadwal) => $jadwal->tanggal >= $now) 
            ->sortBy(fn($jadwal) => $jadwal->tanggal) 
            ->take(2)
            ->values(); 

        // 3. Kirim 'skemas' DAN 'jadwals' ke view
        return view('landing_page.home', compact('skemas', 'jadwals'));
    }

    /**
     * Menampilkan detail skema (TIDAK BERUBAH).
     */
    public function show($id): View
    {
        $skemas = [
        ['id' => 1, 'nama' => 'Junior Web Developer', 'gambar' => 'skema1.jpg', 'deskripsi' => 'Ujian mencakup HTML, CSS, JS, dan PHP dasar.'],
        ['id' => 2, 'nama' => 'Network Administrator', 'gambar' => 'skema4.jpg', 'deskripsi' => 'Menguji kemampuan mengatur jaringan komputer dan server.'],
        ['id' => 3, 'nama' => 'Database Engineer', 'gambar' => 'skema3.jpg', 'deskripsi' => 'Menguji desain dan implementasi basis data relasional.'],
        ['id' => 4, 'nama' => 'UI/UX Designer', 'gambar' => 'skema2.jpg', 'deskripsi' => 'Ujian mencakup riset pengguna dan pembuatan prototipe desain.'],
        ['id' => 5, 'nama' => 'Cyber Security', 'gambar' => 'skema5.jpg', 'deskripsi' => 'Ujian tentang keamanan jaringan, analisis ancaman, dan mitigasi.'],
        ['id' => 6, 'nama' => 'Mobile Developer', 'gambar' => 'skema6.jpg', 'deskripsi' => 'Menguji kemampuan membangun aplikasi mobile Android/iOS.'],
        ['id' => 7, 'nama' => 'Data Analyst', 'gambar' => 'skema7.jpg', 'deskripsi' => 'Menguji kemampuan analisis data dan visualisasi menggunakan tools populer.'],
        ['id' => 8, 'nama' => 'Game Developer', 'gambar' => 'skema8.jpg', 'deskripsi' => 'Menguji kemampuan membuat game dengan engine seperti Unity.'],
        ['id' => 9, 'nama' => 'IoT Specialist', 'gambar' => 'skema9.jpg', 'deskripsi' => 'Menguji pemrograman dan integrasi sensor IoT.'],
        ['id' => 10, 'nama' => 'Cloud Engineer', 'gambar' => 'skema10.jpg', 'deskripsi' => 'Menguji kemampuan membangun dan mengelola layanan cloud.'],
        ['id' => 11, 'nama' => 'AI Engineer', 'gambar' => 'skema11.jpg', 'deskripsi' => 'Menguji kemampuan machine learning dan penerapannya.'],
        ['id' => 12, 'nama' => 'Fullstack Developer', 'gambar' => 'skema12.jpg', 'deskripsi' => 'Ujian menggabungkan frontend, backend, dan integrasi database.'],
        ];

         // Cari skema berdasarkan ID
        $skema = collect($skemas)->firstWhere('id', (int)$id);

        // Jika tidak ditemukan, tampilkan 404
        if (!$skema) {
            abort(404, 'Skema tidak ditemukan');
        }

        return view('landing_page.detail.detail_skema', compact('skema'));
    }

    // ... fungsi jadwal() dan laporan() tidak berubah ...
    public function jadwal(): View { return view('landing_page.frontend.jadwal'); }
    public function laporan(): View { return view('landing_page.frontend.laporan'); }


    /**
     * Menampilkan detail jadwal (Sesuai ID dari dummy data).
     */
    public function showJadwalDetail($id): View
    {
        // Cari jadwal dari array dummy
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $jadwal = collect($semuaJadwal)->firstWhere('id', (int)$id);

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        // Kirim satu objek jadwal dummy ke view
        return view('landing_page.detail.detail_jadwal', [
            'jadwal' => $jadwal
        ]);
    }
}
