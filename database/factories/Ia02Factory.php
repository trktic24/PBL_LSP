<?php

namespace Database\Factories;

use App\Models\Ia02;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class Ia02Factory extends Factory
{
    /**
     * Nama Model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = Ia02::class;

    /**
     * Tentukan definisi default state model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
      
        return [
            // Jika Anda ingin membuat relasi secara otomatis di sini (hanya jika yakin data induk ada)
            // 'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),
            'skenario' => fake()->sentence(10),
            'peralatan' => fake()->sentence(10),
            'waktu' => $this->faker->time('H:i:s'),
        ];
    }

    /**
     * State kustom untuk memastikan IA02 terhubung ke DataSertifikasiAsesi yang sudah ada.
     */
    public function forAsesi(DataSertifikasiAsesi $dataSertifikasiAsesi): Factory
    {
        return $this->state(function (array $attributes) use ($dataSertifikasiAsesi) {
            return [
                'id_data_sertifikasi_asesi' => $dataSertifikasiAsesi->id_data_sertifikasi_asesi,
            ];
        });
    }
}