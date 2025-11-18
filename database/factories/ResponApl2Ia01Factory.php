<?php

namespace Database\Factories;

use App\Models\ResponApl2Ia01;
// use App\Models\DataSertifikasiAsesi;
use App\Models\KriteriaUnjukKerja;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResponApl02Ia01>
 */
class ResponApl2Ia01Factory extends Factory
{
    /**
     * Model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = ResponApl2Ia01::class;

    /**
     * Mendefinisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // // Mengambil ID acak dari tabel DataSertifikasiAsesi
            // 'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),

            // Mengambil ID acak dari tabel Kriteria
            'id_kriteria' => KriteriaUnjukKerja::factory(),

            // Contoh data palsu (faker)
            'respon_asesi_apl02' => $this->faker->sentence(3),
            'bukti_asesi_apl02' => $this->faker->word(),
            'pencapaian_ia01' => $this->faker->boolean(),
            'penilaian_lanjut_ia01' => $this->faker->boolean(),
        ];
    }
}
