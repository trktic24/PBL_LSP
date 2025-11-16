<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Skema;
use App\Models\Category;
use App\Models\Berita; // Model Berita sudah di-import
use App\Models\Jadwal;
use Carbon\Carbon;
use \DateTime;
use Illuminate\Support\Collection;

// 'use App\Http\Controllers\Controller;' SUDAH DIHAPUS DARI SINI
class HomeController extends Controller
{
    // Ini tetap dummy karena belum kita migrasikan
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

    // FUNGSI DUMMY BERITA SUDAH DIHAPUS

    public function index(): View
    {
        $categories = Category::all()->pluck('nama_kategori')->all();
        array_unshift($categories, 'Semua');

        // Ambil data dari tabel skema
        $skemas = Skema::with('category')->latest()->get(); // ini ambil semua dari database faker

        // Ambil 3 jadwal terdekat dari database
        $today = Carbon::now()->startOfDay();

        $jadwals = Jadwal::with(['skema', 'masterTuk']) // Load relasi
            ->where('tanggal_pelaksanaan', '>=', $today) // Hanya jadwal yang akan datang
            ->orderBy('tanggal_pelaksanaan', 'asc') // Urutkan dari yang paling dekat
            ->take(9) // Ambil 3
            ->get();

        // 🟦 MODIFIKASI: AMBIL BERITA DARI DATABASE 🟦
        $beritas = Berita::latest()->take(3)->get();

        // KIRIM BERITA KE VIEW (MODIFIKASI)
        return view('landing_page.home', compact('skemas', 'jadwals', 'categories', 'beritas'));
    }

    public function filterSkema(Request $request)
    {
        $categoryId = $request->input('category_id');

        $category = Category::where('nama_kategori', $categoryId)->first();
        $query = Skema::with('category');

        if ($categoryId !== 'Semua' && $category) {
            $query->where('category_id', $category->id);
        }
        
        $skemas = $query->get();
    }
    public function show($id): View
    {
        // Cari skema berdasarkan ID dari database
        $skema = Skema::findOrFail($id); 
        
        // --- INJEKSI DUMMY DATA UNTUK KONTEN (UNIT KOMPETENSI & SKKNI) ---
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

    // 🟦 MODIFIKASI: FUNGSI DETAIL BERITA DARI DATABASE 🟦
    public function showBeritaDetail($id): View
    {
        // Ambil dari DB atau tampilkan 404 jika tidak ketemu
        $berita = Berita::findOrFail($id); 

        return view('landing_page.detail.berita_detail', [
            'berita' => $berita
        ]);
    }
}
// <-- KARAKTER '}' EKSTRA SUDAH DIHAPUS DARI SINI