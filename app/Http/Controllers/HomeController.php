<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Skema;
use App\Models\Category;
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

    private function getSemuaDummyBerita()
    {
        return [
            (object)[
                'id' => 1,
                'judul' => 'LSP Polines Adakan Uji Kompetensi Skema Jaringan',
                'tanggal' => now()->subDays(2),
                'gambar' => 'https://placehold.co/600x400/96C9F4/white?text=Berita+1'
            ],
            (object)[
                'id' => 2,
                'judul' => 'Kerjasama Baru LSP Polines dengan Industri Digital',
                'tanggal' => now()->subDays(5),
                'gambar' => 'https://placehold.co/600x400/FACC15/white?text=Berita+2'
            ],
            (object)[
                'id' => 3,
                'judul' => 'Sosialisasi Pentingnya Sertifikasi di Era Industri 4.0',
                'tanggal' => now()->subWeeks(1),
                'gambar' => 'https://placehold.co/600x400/3B82F6/white?text=Berita+3'
            ],
        ];
    }
    public function index(): View
    {
        $categories = Category::all()->pluck('nama_kategori')->all();
        array_unshift($categories, 'Semua');

        // Ambil data dari tabel skema
        $skemas = Skema::with('category')->latest()->get(); // ini ambil semua dari database faker

        // Ambil jadwal dummy
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $now = new DateTime();

        $jadwals = collect($semuaJadwal)
            ->filter(fn($jadwal) => $jadwal->tanggal >= $now)
            ->sortBy(fn($jadwal) => $jadwal->tanggal)
            ->take(2)
            ->values();

        // AMBIL BERITA (MODIFIKASI)
        $beritas = collect($this->getSemuaDummyBerita())->take(3);

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
    // FUNGSI BARU UNTUK MENANGANI DETAIL BERITA
    public function showBeritaDetail($id): View
    {
        $semuaBerita = $this->getSemuaDummyBerita();
        $berita = collect($semuaBerita)->firstWhere('id', (int)$id);

        if (!$berita) {
            abort(404, 'Berita tidak ditemukan');
        }

        return view('landing_page.detail.berita_detail', [
            'berita' => $berita
        ]);
    }
}