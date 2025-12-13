<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Asesor;
use Illuminate\Support\Facades\Storage;

class AsesorProfileController extends Controller
{
    // ==========================================================
    // HALAMAN PROFIL SETTINGS
    // ==========================================================
    public function showProfile($id_asesor)
    {
        $asesor = Asesor::with('user')->findOrFail($id_asesor);
        return view('profile_asesor.asesor_profile_settings', compact('asesor'));
    }

    // ==========================================================
    // HALAMAN BUKTI KELENGKAPAN
    // ==========================================================
    public function showBukti($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);
        
        $documents = [
            ['key' => 'ktp', 'title' => 'KTP', 'subtitle' => 'Kartu Tanda Penduduk', 'file_path' => $asesor->ktp],
            ['key' => 'pas_foto', 'title' => 'Foto', 'subtitle' => 'Pas Foto 3x4', 'file_path' => $asesor->pas_foto],
            ['key' => 'NPWP_foto', 'title' => 'NPWP', 'subtitle' => 'Kartu NPWP', 'file_path' => $asesor->NPWP_foto],
            ['key' => 'rekening_foto', 'title' => 'Rekening', 'subtitle' => 'Buku Rekening Bank', 'file_path' => $asesor->rekening_foto],
            ['key' => 'CV', 'title' => 'Curiculum Vitae (CV)', 'subtitle' => 'CV terbaru', 'file_path' => $asesor->CV],
            ['key' => 'ijazah', 'title' => 'Ijazah Pendidikan', 'subtitle' => 'Ijazah pendidikan terakhir', 'file_path' => $asesor->ijazah],
            ['key' => 'sertifikat_asesor', 'title' => 'Sertifikat Asesor Kompetensi', 'subtitle' => 'Sertifikat kompetensi', 'file_path' => $asesor->sertifikat_asesor],
            ['key' => 'sertifikasi_kompetensi', 'title' => 'Sertifikasi Kompetensi', 'subtitle' => 'Sertifikat teknis', 'file_path' => $asesor->sertifikasi_kompetensi],
        ];

        return view('profile_asesor.asesor_profile_bukti', compact('asesor', 'documents'));
    }

    // ==========================================================
    // HALAMAN TINJAUAN ASESMEN
    // ==========================================================
    public function showTinjauan($id_asesor)
    {
        $asesor = Asesor::with('skemas')->findOrFail($id_asesor);
        
        // Data Dummy untuk UI (Sekarang ada id_jadwal dummy)
        $tinjauan_data = collect([
            (object) ['no' => 1, 'id_jadwal' => 101, 'skema' => 'Pemrograman Web Junior', 'status' => 1],
            (object) ['no' => 2, 'id_jadwal' => 102, 'skema' => 'Network Administrator Muda', 'status' => 0],
            (object) ['no' => 3, 'id_jadwal' => 103, 'skema' => 'Desainer Grafis Utama', 'status' => 0],
        ]);

        return view('profile_asesor.asesor_profile_tinjauan', compact('asesor', 'tinjauan_data'));
    }

    // ==========================================================
    // HALAMAN DAFTAR ASESI (DARI JADWAL) - BARU
    // ==========================================================
    public function showDaftarAsesi($id_asesor, $id_jadwal)
    {
        // 1. Ambil Data Asesor (Untuk Sidebar)
        $asesor = Asesor::findOrFail($id_asesor);

        // 2. Ambil Data Jadwal (Untuk konten utama)
        // --- DATA DUMMY JADWAL (Simulasi) ---
        $jadwal = new class {
            public $id_jadwal = 101;
            public $tanggal_pelaksanaan = '2023-11-20';
            public $waktu_mulai = '08:00:00';
            public $skema;
            public $tuk;
            public $dataSertifikasiAsesi;

            public function __construct() {
                $this->skema = (object) ['nama_skema' => 'Pemrograman Web Junior', 'nomor_skema' => 'SKM-001'];
                $this->tuk = (object) ['nama_lokasi' => 'Lab Komputer 1'];
                
                // Dummy Data Asesi
                $this->dataSertifikasiAsesi = collect([
                    (object)[
                        'id_data_sertifikasi_asesi' => 1,
                        'asesi' => (object)['nama_lengkap' => 'Budi Santoso'],
                        'responApl2Ia01' => true, 'responBuktiAk01' => true, // Pra Asesmen Done
                        'lembarJawabIa05' => true, 'komentarAk05' => true,   // Asesmen Done
                        'rekomendasi_apl02' => 'diterima'
                    ],
                    (object)[
                        'id_data_sertifikasi_asesi' => 2,
                        'asesi' => (object)['nama_lengkap' => 'Siti Aminah'],
                        'responApl2Ia01' => true, 'responBuktiAk01' => false, // Pra Asesmen Proses
                        'lembarJawabIa05' => false, 'komentarAk05' => false,
                        'rekomendasi_apl02' => 'belum'
                    ]
                ]);
            }
        };

        // Logika sederhana untuk tombol berita acara (Dummy)
        $semuaSudahAdaKomentar = true; 

        if (view()->exists('profile_asesor.daftar_asesi')) {
             return view('profile_asesor.daftar_asesi', compact('asesor', 'jadwal', 'semuaSudahAdaKomentar'));
        } else {
             return view('daftar_asesi', compact('asesor', 'jadwal', 'semuaSudahAdaKomentar'));
        }
    }

    // ==========================================================
    // 1. TRACKER PROFIL ASESOR (Timeline) - PERBAIKAN DI SINI
    // ==========================================================
    public function showTracker($id_asesor)
    {
        $asesor = Asesor::findOrFail($id_asesor);

        // DATA DUMMY TIMELINE (Sesuai gambar Tracker Asesor.jpg)
        $timelineData = [
            [
                'title' => 'Formulir Pendaftaran Sertifikasi',
                'date' => 'Jumat, 29 September 2025',
                'status_text' => 'Diterima',
                'icon' => 'far fa-user',
            ],
            [
                'title' => 'Pra-Asesmen',
                'date' => 'Jumat, 29 September 2025',
                'status_text' => 'Diterima',
                'icon' => 'fas fa-paperclip',
            ],
            [
                'title' => 'Verifikasi TUK',
                'date' => 'Jumat, 29 September 2025',
                'status_text' => 'Diterima',
                'icon' => 'fas fa-map-marked-alt',
            ],
            [
                'title' => 'Persetujuan Asesmen dan Kerahasiaan',
                'date' => 'Jumat, 29 September 2025',
                'status_text' => 'Diterima',
                'icon' => 'fas fa-file-contract',
            ],
            [
                'title' => 'Asesmen',
                'date' => '',
                'status_text' => '',
                'icon' => 'far fa-edit',
                'sub_items' => [
                    [
                        'title' => 'Cek Observasi - Demontrasi/Praktek',
                        'date' => 'Jumat, 29 September 2025 15.16',
                        'status' => 'Rekomendasi Kompeten'
                    ],
                    [
                        'title' => 'Pertanyaan Lisan',
                        'date' => 'Jumat, 29 September 2025 15.16',
                        'status' => 'Rekomendasi Kompeten'
                    ]
                ]
            ],
            [
                'title' => 'Keputusan dan umpan balik Asesor',
                'date' => 'Jumat, 29 September 2025',
                'status_text' => 'Diterima',
                'icon' => 'far fa-user',
            ],
            [
                'title' => 'Umpan balik peserta / banding',
                'date' => 'Jumat, 29 September 2025',
                'status_text' => 'Diterima',
                'icon' => 'fas fa-paperclip',
            ],
            [
                'title' => 'Keputusan Komite',
                'date' => '',
                'status_text' => '',
                'icon' => 'fas fa-paperclip',
                'footer_text' => 'Direkomendasikan Menerima Sertifikat'
            ],
        ];

        // MENGIRIM $timelineData KE VIEW TRACKER
        return view('profile_asesor.asesor_profile_tracker', compact('asesor', 'timelineData'));
    }

    // ==========================================================
    // 2. DETAIL ASESMEN ASESI (FR.APL.01, dll)
    // ==========================================================
    public function showAssessmentDetail($id_asesor, $id_data_sertifikasi_asesi)
    {
        $asesor = Asesor::findOrFail($id_asesor);

        // DATA DUMMY UNTUK DETAIL ASESMEN (FR.APL.01, dll)
        $dataSertifikasi = new class {
            public $id_data_sertifikasi_asesi = 12345; 
            public $level_status = 40; 
            public $rekomendasi_apl01 = 'diterima';
            public $rekomendasi_hasil_asesmen_AK02 = 'kompeten';
            public $asesi;
            public $responbuktiAk01;

            public function __construct() {
                $this->asesi = (object) [
                    'id_asesi' => 99, 
                    'nama_lengkap' => 'Budi Santoso'
                ];
                $this->responbuktiAk01 = collect([ (object)['respon' => 'Valid'] ]);
            }
            
            // Mock methods untuk mencegah error di view detail
            public function ia10() { return new class { public function exists() { return true; } }; }
            public function ia02() { return new class { public function exists() { return false; } }; }
            public function ia07() { return new class { public function exists() { return true; } }; }
            public function ia06Answers() { return new class { public function count() { return 5; } }; }
        };

        $asesi = $dataSertifikasi->asesi;

        // MENGIRIM $dataSertifikasi KE VIEW DETAIL
        return view('profile_asesor.asesor_assessment_detail', compact('asesor', 'dataSertifikasi', 'asesi'));
    }

    // ==========================================================
    // UPDATE STATUS VERIFIKASI ASESOR (APPROVED/REJECTED)
    // ==========================================================
    public function updateStatus(Request $request, $id_asesor)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:approved,rejected,pending',
        ]);

        $asesor = Asesor::findOrFail($id_asesor);
        $asesor->status_verifikasi = $request->status_verifikasi;
        $asesor->save();

        return redirect()->back()->with('success', 'Status verifikasi asesor berhasil diperbarui.');
    }
}