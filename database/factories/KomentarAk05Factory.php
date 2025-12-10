<?php

namespace Database\Factories;

use App\Models\KomentarAk05;
use App\Models\DataSertifikasiAsesi;
use App\Models\Ak05;
use Illuminate\Database\Eloquent\Factories\Factory;

class KomentarAk05Factory extends Factory
{
    protected $model = KomentarAk05::class;    

    public function definition(): array
    {
        $faker = $this->faker;

        // Ambil semua id dari tabel yang sudah ada
        $dataSertifikasiIds = DataSertifikasiAsesi::pluck('id_data_sertifikasi_asesi');
        $ak05Ids = Ak05::pluck('id_ak05');

        return [
            // Ambil ID acak dari data yang sudah ada
            'id_data_sertifikasi_asesi' => $dataSertifikasiIds->random(),
            'id_ak05' => $ak05Ids->random(),

            'rekomendasi' => $faker->randomElement(['K', 'BK']),
            'keterangan' => $faker->optional(0.9)->text(500),
        ];
    }
}
