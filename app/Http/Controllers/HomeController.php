<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Skema;
use \DateTime;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    private function getSemuaDummyJadwal()
    {
        return [
            (object) [
                'id' => 1,
                'nama_skema' => 'Network Engineering',
                'tanggal' => new DateTime('2025-12-15'),
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '16:00',
                'tuk' => 'Politeknik Negeri Semarang',
                'deskripsi' => 'Deskripsi lengkap untuk skema sertifikasi Network Engineering...',
                'persyaratan' => '1. Mahasiswa Aktif Polines...',
                'harga' => 350000,
                'tanggal_tutup' => new DateTime('2025-12-01')
            ],
            (object) [
                'id' => 2,
                'nama_skema' => 'Junior Web Developer',
                'tanggal' => new DateTime('2025-11-30'),
                'waktu_mulai' => '09:00',
                'waktu_selesai' => '17:00',
                'tuk' => 'Lab RPL Gedung D4',
                'deskripsi' => 'Deskripsi untuk Junior Web Developer...',
                'persyaratan' => '1. Terbuka untuk umum...',
                'harga' => 500000,
                'tanggal_tutup' => new DateTime('2025-11-20')
            ],
        ];
    }

    public function index(): View
    {
        // Ambil data dari tabel skema
        $skemas = Skema::latest()->get(); // ini ambil semua dari database faker

        // Ambil daftar kategori unik dari kolom 'kategori'
        $categories = $skemas->pluck('kategori')->unique()->values()->all();
        array_unshift($categories, 'Semua');

        // Ambil jadwal dummy
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $now = new DateTime();

        $jadwals = collect($semuaJadwal)
            ->filter(fn($jadwal) => $jadwal->tanggal >= $now)
            ->sortBy(fn($jadwal) => $jadwal->tanggal)
            ->take(2)
            ->values();

        return view('landing_page.home', compact('skemas', 'jadwals', 'categories'));
    }

    public function show($id): View
    {
        // Cari skema berdasarkan ID dari database
        $skema = Skema::findOrFail($id); 
        
        // --- INJEKSI DUMMY DATA UNTUK KONTEN (UNIT KOMPETENSI & SKKNI) ---
        // Ini diperlukan agar detail_skema.blade.php dapat melakukan looping 
        // pada bagian Unit Kompetensi dan SKKNI.

        $skema->unit_kompetensi = collect([
            (object) [
                'kode' => '123456789',
                'judul' => [
                    'Mengidentifikasi Konsep Keamanan Jaringan',
                    'Menerapkan prosedur keamanan dasar jaringan',
                ]
            ],
            (object) [
                'kode' => '987654321',
                'judul' => [
                    'Menganalisis potensi ancaman keamanan sistem',
                    'Mengimplementasikan pengamanan berbasis firewall',
                ]
            ]
        ]);

        $skema->skkni = collect([
            // Asumsi properti 'link_pdf' akan diisi link unduhan dokumen
            (object) ['nama' => 'SKKNI Keamanan Siber 1', 'link_pdf' => '#'],
            (object) ['nama' => 'SKKNI Keamanan Siber 2', 'link_pdf' => '#'],
        ]);
        
        // Memastikan properti dasar untuk Hero Section tersedia (jika tidak ada di model)
        if (!isset($skema->gambar)) {
             $skema->gambar = 'default_skema_image.jpg'; 
        }
        if (!isset($skema->deskripsi)) {
             $namaSkema = $skema->nama_skema ?? $skema->nama ?? 'Skema Sertifikasi';
             $skema->deskripsi = 'Deskripsi singkat mengenai ' . $namaSkema . '.';
        }
        
        // Cari jadwal yang sesuai dengan nama skema
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $jadwalTerkait = collect($semuaJadwal)
            ->firstWhere('nama_skema', $skema->nama_skema);

        return view('landing_page.detail.detail_skema', compact('skema', 'jadwalTerkait'));
    }

    public function jadwal(): View
    {
        return view('landing_page.frontend.jadwal');
    }

    public function laporan(): View
    {
        return view('landing_page.frontend.laporan');
    }

    public function showJadwalDetail($id): View
    {
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $jadwal = collect($semuaJadwal)->firstWhere('id', (int)$id);

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        return view('landing_page.detail.detail_jadwal', [
            'jadwal' => $jadwal
        ]);
    }
}