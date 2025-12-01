<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\Skema;
use App\Models\Category;
use App\Models\Jadwal; 
use App\Models\Berita; 
use Carbon\Carbon;
use \DateTime;

class HomeController extends Controller
{

    /**
     * Landing Page utama
     */
    public function index(): View
    {
        $categories = Category::all()->pluck('nama_kategori')->all();
        array_unshift($categories, 'Semua');

        // Ambil data dari tabel skema
        $skemas = Skema::with('category')->latest()->get(); 

        // Ambil 3 jadwal terdekat dari database yang akan datang
        $today = Carbon::now()->startOfDay();

        $jadwals = Jadwal::with(['skema', 'masterTuk']) // Load relasi
            ->where('tanggal_pelaksanaan', '>=', $today) // Hanya jadwal yang akan datang
            ->where('Status_jadwal', 'Terjadwal') // Hanya Jadwal dengan status Terjadwal aja yang ditampilkan
            ->orderBy('tanggal_pelaksanaan', 'asc') // Urutkan dari yang paling dekat
            ->take(9) // Ambil 9 (untuk 3 slide)
            ->get();

        // 🟦 MODIFIKASI: AMBIL BERITA DARI DATABASE 🟦
        $beritas = Berita::latest()->take(12)->get();

        // KIRIM DATA KE VIEW
        return view('landing_page.home', compact('skemas', 'jadwals', 'categories', 'beritas'));
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

        // Data dummy SKKNI
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
        
        // Mengarahkan ke file view detail_skema.blade.php
        return view('landing_page.detail.detail_skema', compact('skema')); 
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
        
        // Menggunakan accessor dari model Jadwal.php (sudah Carbon object)
        $tanggal_pelaksanaan = $jadwal->tanggal_pelaksanaan; 
        
        // Pengecekan null-safe. Jika null, anggap sudah lewat (true)
        $is_past = $tanggal_pelaksanaan ? $tanggal_pelaksanaan->isPast() : true; 

        // Mengarahkan ke file view detail_jadwal.blade.php
        return view('landing_page.detail.detail_jadwal', [
            'jadwal' => $jadwal,
            'is_past' => $is_past // Kirim status apakah jadwal sudah lewat
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