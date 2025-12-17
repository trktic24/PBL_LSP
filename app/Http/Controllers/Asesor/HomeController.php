<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Asesor;
use App\Models\Jadwal;
use App\Models\Skema;
use Illuminate\View\View;
use DateTime;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    // FUNGSI indexAsesor() DIHAPUS KARENA TIDAK TERPAKAI DAN MEMBINGUNGKAN

    /**
     * Landing Page utama
     */
    public function index(): View
    {
        // Ambil data Skema
        $skemas = Skema::latest()->get();

        // Buat daftar kategori unik
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

    /**
     * Detail Skema
     */
    public function show($id): View
    {
        $skema = Skema::findOrFail($id);

        // Data dummy unit kompetensi
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

        // Default Hero Section
        $skema->gambar ??= 'default_skema_image.jpg';
        $skema->deskripsi ??= 'Deskripsi singkat mengenai ' . ($skema->nama_skema ?? 'Skema Sertifikasi') . '.';

        // Jadwal terkait
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $jadwalTerkait = collect($semuaJadwal)
            ->firstWhere('nama_skema', $skema->nama_skema);

        return view('landing_page.detail.detail_skema', compact('skema', 'jadwalTerkait'));
    }

    /**
     * Halaman daftar Jadwal (Publik)
     * Fungsi ini sudah benar dan sekarang dipanggil oleh rute /jadwal
     */
    public function jadwal(): View
    {
        $jadwalList = \App\Models\Jadwal::with('skema', 'masterTuk')
                    ->where('tanggal_mulai', '>=', now()) // Ambil jadwal mendatang
                    ->orderBy('tanggal_mulai', 'asc')
                    ->paginate(10); // Gunakan paginate untuk daftar panjang

        // 2. Kirim data ke view yang BENAR ('landing_page.jadwal')
        //    dengan nama variabel yang BENAR ('jadwalList')
        return view('landing_page.jadwal', [
            'jadwalList' => $jadwalList
        ]);
    }

    public function laporan(): View
    {
        return view('landing_page.frontend.laporan');
    }


    /**
     * --- FUNGSI BARU (PERBAIKAN) ---
     * Menampilkan detail jadwal untuk PUBLIK.
     * Fungsi ini menggantikan fungsi 'showJadwalDetail' Anda yang lama (yang isinya salah).
     */
    public function showJadwalDetailPublik($id): View
    {
        // Menggunakan data dummy Anda, tapi tanpa memerlukan Auth::user()
        $semuaJadwal = $this->getSemuaDummyJadwal();
        $jadwal = collect($semuaJadwal)->firstWhere('id', (int)$id);

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        // Di sini kita bisa ambil data skema terkait jika perlu
        $skema = Skema::where('nama_skema', $jadwal->nama_skema)->first();

        // Pastikan Anda punya view 'detail_jadwal' di folder landing_page
        return view('landing_page.detail.detail_jadwal', compact('jadwal', 'skema'));
    }


    /**
     * --- FUNGSI LAMA ANDA YANG SALAH ---
     * Fungsi ini sekarang TIDAK terpakai oleh rute publik.
     * Isinya adalah logika untuk dashboard asesor, yang seharusnya ada di AsesorDashboardController.
     * Saya biarkan di sini agar tidak error jika ada rute lain yang masih memanggilnya.
     */
    public function showJadwalDetail($id): View
    {
        // Logika ini salah, ini adalah logika untuk dashboard asesor,
        // bukan untuk halaman detail jadwal publik.
        $asesor = Asesor::where('user_id', Auth::user()->id_user)
            ->with('skemas')
            ->first();

        $semuaJadwal = $this->getSemuaDummyJadwal();
        $jadwal = collect($semuaJadwal)->firstWhere('id', (int)$id);

        if (!$jadwal) {
            abort(404, 'Jadwal tidak ditemukan');
        }

        $profile = [ /* ... */ ];
        $summary = [ /* ... */ ];
        $jadwals = Jadwal::where('id_asesor', $asesor->id_asesor)
            ->limit(5)
            ->get();
        if ($jadwals->isEmpty()) {
            $jadwals = [ /* ... */ ];
        } else {
            $jadwals = $jadwals->map(function ($item) {
                return (object)[ /* ... */ ];
            });
        }

        // Ini mengembalikan view 'frontend.home' (DASHBOARD ASESOR)
        // Inilah yang menyebabkan masalah Anda.
        return view('frontend.home', compact('profile', 'summary', 'jadwals'));
    }

    /**
     * DUMMY Jadwal
     */
    private function getSemuaDummyJadwal()
    {
        return [
            (object)[
                'id' => 1,
                'nama_skema' => 'Network Engineering',
                'tanggal' => new DateTime('2025-12-15'),
                'tuk' => 'Polines',
            ],
            (object)[
                'id' => 2,
                'nama_skema' => 'Junior Web Developer',
                'tanggal' => new DateTime('2025-11-30'),
                'tuk' => 'Lab RPL',
            ],
        ];
    }
}