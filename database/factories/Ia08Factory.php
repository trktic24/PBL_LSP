<?php

namespace Database\Factories;

use App\Models\Ia08;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class Ia08Factory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Ia08::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // Mencari ID Data Sertifikasi Asesi yang sudah ada secara acak
        // Fallback ke 1 jika tidak ada data (pastikan ID 1 ada di tabel Anda)
        $id_asesi = DataSertifikasiAsesi::inRandomOrder()->value('id_data_sertifikasi_asesi') ?? 1;

        return [
            'id_data_sertifikasi_asesi' => $id_asesi,
            
            // Menggunakan kalimat pendek (sentence(5)) untuk mengatasi Data Truncated
            // Ini akan membuat string pendek, aman untuk kolom VARCHAR(255)
            'bukti_tambahan' => $this->faker->sentence(5), 
            
            // Menggunakan opsi rekomendasi yang singkat
            'rekomendasi' => $this->faker->randomElement([
                'kompeten',
                'perlu observasi langsung',
            ]),
        ];
    }
}