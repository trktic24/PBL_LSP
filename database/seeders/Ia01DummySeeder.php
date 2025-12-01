<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Skema;
use App\Models\KelompokPekerjaan;
use App\Models\UnitKompetensi;
use App\Models\Elemen;
use App\Models\KriteriaUnjukKerja;

class Ia01DummySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bikin SKEMA
        $skema = Skema::factory()->create([
            'nama_skema' => 'Junior Web Developer',
        ]);

        // 2. Bikin KELOMPOK (Sambungin ke Skema)
        $kelompok = KelompokPekerjaan::factory()->create([
            'id_skema' => $skema->id_skema,
            'nama_kelompok_pekerjaan' => 'Core Competencies',
        ]);

        // ==========================================
        // STEP 1: Unit "Aktivitas" (Form Ya/Tidak)
        // ==========================================
        $unit1 = UnitKompetensi::factory()->create([
            'id_kelompok_pekerjaan' => $kelompok->id_kelompok_pekerjaan,
            'urutan' => 1, // STEP 1
            'kode_unit' => 'J.620100.004.02',
            'judul_unit' => 'Menggunakan Struktur Data',
        ]);

        // Bikin Elemen & KUK Step 1
        $elemen1 = Elemen::factory()->create(['id_unit_kompetensi' => $unit1->id_unit_kompetensi]);

        KriteriaUnjukKerja::factory()->create([
            'id_elemen' => $elemen1->id_elemen,
            'no_kriteria' => '1.1',
            'kriteria' => 'Konsep struktur data diidentifikasi',
            'tipe' => 'aktivitas', // Tipe YA/TIDAK
        ]);
        KriteriaUnjukKerja::factory()->create([
            'id_elemen' => $elemen1->id_elemen,
            'no_kriteria' => '1.2',
            'kriteria' => 'Alternatif struktur data dibandingkan',
            'tipe' => 'aktivitas',
        ]);

        // ==========================================
        // STEP 2: Unit "Demonstrasi" (Form K/BK)
        // ==========================================
        $unit2 = UnitKompetensi::factory()->create([
            'id_kelompok_pekerjaan' => $kelompok->id_kelompok_pekerjaan,
            'urutan' => 2, // STEP 2
            'kode_unit' => 'J.620100.005.02',
            'judul_unit' => 'Mengimplementasikan User Interface',
        ]);

        // Bikin Elemen & KUK Step 2
        $elemen2 = Elemen::factory()->create(['id_unit_kompetensi' => $unit2->id_unit_kompetensi]);

        KriteriaUnjukKerja::factory()->create([
            'id_elemen' => $elemen2->id_elemen,
            'no_kriteria' => '1.1',
            'kriteria' => 'Desain UI diidentifikasi',
            'tipe' => 'demonstrasi', // Tipe K/BK
        ]);
        KriteriaUnjukKerja::factory()->create([
            'id_elemen' => $elemen2->id_elemen,
            'no_kriteria' => '1.2',
            'kriteria' => 'Komponen UI diterapkan',
            'tipe' => 'demonstrasi',
        ]);
    }
}
