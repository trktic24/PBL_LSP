<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Skema;
use \DateTime;
use Illuminate\Support\Collection;
// Tambahkan model Jadwal jika belum ada
use App\Models\Jadwal;

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
        // ... (Fungsi index Anda tidak diubah)
        $skemas = Skema::latest()->get();
        $categories = $skemas->pluck('kategori')->unique()->values()->all();
        array_unshift($categories, 'Semua');
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
        // [MODIFIKASI 1 DARI 2]
        // Kita ambil skema, DAN juga relasi 'jadwals' (yang kita buat di Model)
        // serta relasi 'tuk' dari setiap jadwal.
        try {
            $skema = Skema::with('jadwals.tuk')->findOrFail($id); 
        } catch (\Exception $e) {
            // Jika skema tidak ditemukan, kembali ke home
            return redirect()->route('home')->with('error', 'Skema tidak ditemukan.');
        }
        
        // --- INJEKSI DUMMY DATA UNTUK KONTEN (UNIT KOMPETENSI & SKKNI) ---
        // Kode dummy data Anda dipertahankan, tidak diubah
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
        
        // [MODIFIKASI 2 DARI 2]
        // Bagian 'jadwalTerkait' ini sudah tidak diperlukan lagi,
        // karena view baru kita me-looping '$skema->jadwals'
        
        // $semuaJadwal = $this->getSemuaDummyJadwal();
        // $jadwalTerkait = collect($semuaJadwal)
        //     ->firstWhere('nama_skema', $skema->nama_skema);

        // [PERBAIKAN]
        // Mengarahkan ke nama view yang benar: 'landing_page.detail.detail_skema'
        return view('landing_page.detail.detail_skema', compact('skema'));
    }

    public function jadwal(): View
    {
        // ... (Fungsi Anda tidak diubah)
        return view('landing_page.frontend.jadwal');
    }

    public function laporan(): View
    {
        // ... (Fungsi Anda tidak diubah)
        return view('landing_page.frontend.laporan');
    }

    public function showJadwalDetail($id): View
    {
        // ... (Fungsi Anda tidak diubah)
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