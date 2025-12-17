<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\Elemen; // Pastikan ini sesuai nama Model lu (Elemen atau MasterElemen)
use App\Models\KriteriaUnjukKerja;
use App\Models\Category;

class JuniorWebDevSeeder extends Seeder
{
    public function run()
    {
        // 1. Bikin CATEGORY
        $kategori = Category::firstOrCreate(
            ['nama_kategori' => 'Pemrograman Web'],
            ['slug' => 'tik-' . rand(100, 999)] // Slug dummy
        );

        // 2. Bikin SKEMA
        $skema = Skema::firstOrCreate(
            ['nomor_skema' => 'SKM-JWD-001'],
            [
                'category_id' => $kategori->id ?? 1,
                'nama_skema' => 'Junior Web Developers',
                'deskripsi_skema' => 'Skema sertifikasi untuk pengembang web tingkat pemula.',
                'harga' => 500000,
                'gambar' => 'default.jpg',
                'SKKNI' => 'file_dummy.pdf',
            ]
        );

        // 3. Bikin KELOMPOK PEKERJAAN
        $kelompok = KelompokPekerjaan::firstOrCreate(
            ['id_skema' => $skema->id_skema],
            ['nama_kelompok_pekerjaan' => 'Junior Web Developer']
        );

        // DATA REALISTIS: 3 Unit Kompetensi
        $daftarUnit = [
            [
                'kode' => 'J.620100.004.02',
                'judul' => 'Menggunakan Struktur Data',
                'urutan' => 1
            ],
            [
                'kode' => 'J.620100.009.02',
                'judul' => 'Mengimplementasikan Pemrograman Terstruktur',
                'urutan' => 2
            ],
            [
                'kode' => 'J.620100.017.02',
                'judul' => 'Menggunakan Library atau Komponen Pre-Existing',
                'urutan' => 3
            ],
        ];

        foreach ($daftarUnit as $dataUnit) {
            // 4. Bikin UNIT
            $unit = UnitKompetensi::firstOrCreate(
                ['kode_unit' => $dataUnit['kode']],
                [
                    'id_kelompok_pekerjaan' => $kelompok->id_kelompok_pekerjaan,
                    'judul_unit' => $dataUnit['judul'],
                    'urutan' => $dataUnit['urutan'],
                ]
            );

            // 5. Bikin 2 ELEMEN per Unit
            for ($i = 1; $i <= 2; $i++) {
                $elemen = Elemen::firstOrCreate(
                    [
                        'id_unit_kompetensi' => $unit->id_unit_kompetensi,
                        'elemen' => "Elemen Kompetensi ke-$i dari Unit " . $unit->kode_unit,
                    ]
                );

                // 6. Bikin 3 KUK per Elemen
                for ($j = 1; $j <= 3; $j++) {
                    KriteriaUnjukKerja::firstOrCreate(
                        [
                            'id_elemen' => $elemen->id_elemen,
                            'no_kriteria' => "$i.$j",
                        ],
                        [
                            'kriteria' => "Asesi mampu melakukan kriteria $i.$j dengan tepat.",
                            'tipe' => 'demonstrasi',
                            'standar_industri_kerja' => null,
                        ]
                    );
                }
            }
        }

        $this->command->info('✅ SUKSES! Data Junior Web Developer berhasil digenerate manual.');
    }
}
