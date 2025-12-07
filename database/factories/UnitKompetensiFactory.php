<?php

namespace Database\Factories;

// [1] Panggil Model-model yang diperlukan
use App\Models\UnitKompetensi;  // <-- Model kamu
use App\Models\KelompokPekerjaan; // <-- Model Induk (Parent)
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UnitKompetensi>
 */
class UnitKompetensiFactory extends Factory
{
    /**
     * Tentukan model yang terhubung.
     *
     * @var string
     */
    protected $model = UnitKompetensi::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_kelompok_pekerjaan' => KelompokPekerjaan::factory(),            
            // Membuat kode unik, sesuai constraint migration ->unique()
            'kode_unit' => fake()->unique()->numerify('J.620100.###.##'),

            // Membuat judul unit kompetensi yang realistis
            'judul_unit' => fake()->randomElement([
                'Menerapkan Prinsip-Prinsip Desain User Interface',
                'Menggunakan Perangkat Lunak Desain Grafis',
                'Menulis Kode Sesuai Kaidah Pemrograman Berorientasi Objek',
                'Melakukan Instalasi Jaringan Komputer Lokal (LAN)',
                'Mengelola Konten Website',
                'Mengidentifikasi Kebutuhan Sistem (Requirement Gathering)',
                'Membuat Laporan Hasil Pengujian Perangkat Lunak',
            ]),
        ];
    }
}