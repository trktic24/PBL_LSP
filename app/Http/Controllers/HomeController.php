<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Skema;
use App\Models\Category;
use App\Models\Jadwal; 
use Illuminate\Support\Collection;
use Carbon\Carbon; // Import Carbon untuk DateTime yang lebih baik

class HomeController extends Controller
{
    // FUNGSI INI HANYA UNTUK BERITA (DATA BERITA MASIH DUMMY)
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
        $skemas = Skema::with('category')->latest()->get(); 

        // [PERUBAHAN KRUSIAL]: MENGAMBIL SEMUA JADWAL (TIDAK HANYA YANG AKAN DATANG)
        // Logika pengecekan tanggal akan dipindahkan ke home.blade.php
        $jadwals = Jadwal::with('skema', 'masterTuk')
                            ->orderBy('tanggal_pelaksanaan', 'asc') 
                            // Kita ambil 3 jadwal (terbaru) untuk memastikan ada yang sudah lewat jika ada
                            ->take(3) 
                            ->get();
                            
        // AMBIL BERITA (MASIH DUMMY)
        $beritas = collect($this->getSemuaDummyBerita())->take(3);

        // KIRIM DATA KE VIEW
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
        // Menggunakan Eager Loading untuk 'jadwal' dan relasi 'masterTuk' di dalamnya
        $skema = Skema::with(['jadwal.masterTuk', 'category', 'asesors'])->findOrFail($id);
        
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
        
        return view('landing_page.detail.detail_skema', compact('skema'));
    }

    public function jadwal(): View
    {
        return view('landing_page.frontend.jadwal');
    }

    public function laporan(): View
    {
        return view('landing_page.frontend.laporan');
    }

    /**
     * Menampilkan halaman detail jadwal dari ID Jadwal di database.
     * @param int $id ID Jadwal
     * @return View
     */
    public function showJadwalDetail($id): View
    {
        // [KODE UTAMA]: MENGAMBIL DETAIL JADWAL DARI DATABASE (MODEL JADWAL)
        $jadwal = Jadwal::with('skema', 'masterTuk')->findOrFail($id); 
        
        // Memastikan tanggal pelaksanaan diubah menjadi objek Carbon untuk perbandingan
        $tanggal_pelaksanaan = Carbon::parse($jadwal->tanggal_pelaksanaan);
        $is_past = $tanggal_pelaksanaan->isPast();

        return view('landing_page.detail.detail_jadwal', [
            'jadwal' => $jadwal,
            'is_past' => $is_past // Kirim status apakah jadwal sudah lewat
        ]);
    }
    
    // FUNGSI UNTUK MENANGANI DETAIL BERITA (MASIH DUMMY)
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