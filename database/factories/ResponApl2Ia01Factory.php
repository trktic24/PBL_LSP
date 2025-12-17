<?php

namespace Database\Factories;

// [1] Panggil SEMUA Model yang diperlukan
use App\Models\KriteriaUnjukKerja;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ResponApl2Ia01;      // <-- Sesuaikan nama modelmu

use App\Models\DataSertifikasiAsesi; // <-- Model Induk (Parent) 1
use App\Models\MasterKriteriaUnjukKerja; // <-- Model Induk (Parent) 2

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResponApl2Ia01>
 */
class ResponApl2Ia01Factory extends Factory
{
    /**
     * Tentukan model yang sesuai.
     */
    protected $model = ResponApl2Ia01::class; // <-- Sesuaikan nama modelmu

    /**
     * Definisikan data palsunya.
     */
    public function definition(): array
    {
        return [
            // --- Foreign Keys (Udah bener) ---
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),
            'id_kriteria' => KriteriaUnjukKerja::factory(),
            
            // --- [INI PERBAIKANNYA] ---
            // Kita ganti dari 'sentence()' dan 'word()' jadi 'numberBetween()'
            // biar ngasilin ANGKA sesuai permintaan migrasi.
            
            'respon_asesi_apl02' => $this->faker->numberBetween(1, 100), // Jadi angka acak
            'bukti_asesi_apl02' => $this->faker->numberBetween(1, 100), // Jadi angka acak

            // --- Kolom sisanya (Udah bener) ---
            'pencapaian_ia01' => $this->faker->boolean(),
            'penilaian_lanjut_ia01' => $this->faker->boolean(),
        ];
    }
}