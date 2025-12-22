<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\Elemen;
use App\Models\KriteriaUnjukKerja;
use App\Models\Jadwal;
use App\Models\DataSertifikasiAsesi;
use App\Models\User;
use App\Models\MasterTuk;
use App\Models\Category;

class TenSchemesSeeder extends Seeder
{
    public function run()
    {
        // 1. Ensure Dependencies Exist
        $asesor = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'asesor'); })->first();
        $asesi = User::whereHas('role', function ($q) {
            $q->where('nama_role', 'asesi'); })->first();
        $tuk = MasterTuk::first();

        if (!$asesor || !$asesi) {
            $this->command->error('❌ Asesor or Asesi user missing. Please run UserSeeder first.');
            return;
        }

        // 2. Define 10 Schemes
        $schemes = [
            ['Junior Web Developer', 'Web Development'],
            ['Network Administrator', 'Networking'],
            ['Graphic Designer', 'Multimedia'],
            ['Data Analyst', 'Data Science'],
            ['Cloud Architect', 'Cloud Computing'],
            ['Cyber Security Analyst', 'Security'],
            ['Mobile App Developer', 'Mobile Development'],
            ['Digital Marketing Specialist', 'Marketing'],
            ['IT Project Manager', 'Management'],
            ['System Analyst', 'System Analysis'],
        ];

        foreach ($schemes as $index => $data) {
            try {
                $namaSkema = $data[0];
                $kategoriName = $data[1];
                $nomorSkema = 'SKM-' . str_pad($index + 1, 3, '0', STR_PAD_LEFT);

                $this->command->info("Creating Scheme: $namaSkema...");

                // A. Create Category
                $kategori = Category::firstOrCreate(
                    ['nama_kategori' => $kategoriName],
                    ['slug' => \Illuminate\Support\Str::slug($kategoriName)]
                );

                // B. Create Skema
                $skema = Skema::firstOrCreate(
                    ['nama_skema' => $namaSkema],
                    [
                        'category_id' => $kategori->id,
                        'nomor_skema' => $nomorSkema,
                        'deskripsi_skema' => "Skema sertifikasi untuk $namaSkema",
                        'harga' => 500000 + ($index * 50000),
                        'gambar' => 'skema/skema' . fake()->numberBetween(1, 15) . '.jpg',
                        'SKKNI' => 'file.pdf',
                    ]
                );

                // C. Create Kelompok Pekerjaan
                $kelompok = KelompokPekerjaan::firstOrCreate(
                    ['id_skema' => $skema->id_skema],
                    ['nama_kelompok_pekerjaan' => 'Core Competencies']
                );

                // D. Create Units (2 Units per Scheme)
                for ($u = 1; $u <= 2; $u++) {
                    $unitCode = "U.{$nomorSkema}.00{$u}";
                    $unit = UnitKompetensi::firstOrCreate(
                        ['kode_unit' => $unitCode],
                        [
                            'id_kelompok_pekerjaan' => $kelompok->id_kelompok_pekerjaan,
                            'judul_unit' => "Kompetensi $u untuk $namaSkema",
                            'urutan' => $u,
                        ]
                    );

                    // E. Create Elemen & KUK (2 Elemen, 2 KUK each)
                    for ($e = 1; $e <= 2; $e++) {
                        $elemen = Elemen::firstOrCreate(
                            [
                                'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                                'elemen' => "Elemen $e dari Unit $u",
                            ]
                        );

                        for ($k = 1; $k <= 2; $k++) {
                            KriteriaUnjukKerja::firstOrCreate(
                                [
                                    'id_elemen' => $elemen->id_elemen,
                                    'no_kriteria' => "$e.$k",
                                ],
                                [
                                    'kriteria' => "Kriteria $e.$k: Mampu melakukan tugas $e.$k",
                                    'tipe' => ($u == 1) ? 'aktivitas' : 'demonstrasi', // Unit 1 Aktivitas, Unit 2 Demo
                                ]
                            );
                        }
                    }
                }

                // F. Create Jadwal
                // Ensure we have a valid Asesor (from Asesor model, not User)
                $asesorModel = \App\Models\Asesor::first();
                if (!$asesorModel) {
                    throw new \Exception('❌ No Asesor found in asesor table. Run AsesorSeeder first.');
                }

                // Ensure we have a valid JenisTuk
                $jenisTuk = \App\Models\JenisTuk::first();
                if (!$jenisTuk) {
                    $jenisTuk = \App\Models\JenisTuk::create(['sewaktu' => '1', 'tempat_kerja' => '0', 'mandiri' => '0']);
                }

                // Ensure we have a valid MasterTuk
                if (!$tuk) {
                    $tuk = \App\Models\MasterTUK::create([
                        'nama_lokasi' => 'TUK Sewaktu',
                        'alamat_tuk' => 'Jl. Contoh No. 1',
                        'kontak_tuk' => '08123456789',
                        'foto_tuk' => 'default.jpg',
                        'link_gmap' => 'https://maps.google.com',
                    ]);
                }

                $jadwal = Jadwal::forceCreate([
                    'id_skema' => $skema->id_skema,
                    'id_asesor' => $asesorModel->id_asesor, // Use ID from Asesor table
                    'id_tuk' => $tuk->id_tuk,
                    'id_jenis_tuk' => $jenisTuk->id_jenis_tuk,
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => now()->addDays(7),
                    'tanggal_pelaksanaan' => now()->addDays(2),
                    'waktu_mulai' => '09:00:00',
                    'waktu_selesai' => '12:00:00',
                    'sesi' => 1,
                    'kuota_maksimal' => 20,
                    'kuota_minimal' => 5,
                    'Status_jadwal' => 'Terjadwal',
                ]);

                // G. Create DataSertifikasiAsesi (The Ticket)
                // Ensure we have a valid Asesi
                $asesiModel = \App\Models\Asesi::first();
                if (!$asesiModel) {
                    throw new \Exception('❌ No Asesi found in asesi table. Run AsesiSeeder first.');
                }

                $sertifikasi = DataSertifikasiAsesi::create([
                    'id_jadwal' => $jadwal->id_jadwal,
                    'id_asesi' => $asesiModel->id_asesi,
                    'tanggal_daftar' => now(),
                    // Required Enums
                    'rekomendasi_apl01' => 'diterima',
                    'tujuan_asesmen' => 'sertifikasi',
                    'rekomendasi_apl02' => 'diterima',
                    'jawaban_mapa01' => 'pekerjaan',
                    'karakteristik_kandidat' => 'tidak ada',
                    'kebutuhan_kontekstualisasi_terkait_tempat_kerja' => 'tidak ada',
                    'Saran_yang_diberikan_oleh_paket_pelatihan' => 'tidak ada',
                    'penyesuaian_perangkat_asesmen' => 'tidak ada',
                    'peluang_kegiatan_asesmen_terintegrasi_dan_perubahan_alat_asesmen' => 'tidak ada',
                    'rekomendasi_IA04B' => 'kompeten',
                    'rekomendasi_hasil_asesmen_AK02' => 'kompeten',
                    'rekomendasi_AK05' => 'kompeten',
                    // Required Text Fields
                    'tindakan_lanjut_AK02' => '-',
                    'komentar_AK02' => '-',
                    'catatan_asesi_AK03' => '-',
                    'keterangan_AK05' => '-',
                    'aspek_dalam_AK05' => '-',
                    'catatan_penolakan_AK05' => '-',
                    'saran_dan_perbaikan_AK05' => '-',
                    'catatan_AK05' => '-',
                    'rekomendasi1_AK06' => '-',
                    'rekomendasi2_AK06' => '-',
                    // Optional but good to have
                    'feedback_ia01' => 'Peserta sangat kompeten',
                    'rekomendasi_ia01' => 'Lanjut ke tahap berikutnya',
                    'status_sertifikasi' => 'pendaftaran_selesai',
                ]);

                $this->command->info("   ✅ Created URL: /ia01/{$sertifikasi->id_data_sertifikasi_asesi}/cover");

            } catch (\Exception $e) {
                $this->command->error("❌ Failed to create scheme {$data[0]}");
                file_put_contents('seeder_error.log', $e->getMessage() . "\n" . $e->getTraceAsString() . "\n\n", FILE_APPEND);
            }
        }
    }
}
