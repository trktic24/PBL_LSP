<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JawabanIa06Factory extends Factory
{
    public function definition(): array
    {
        return [
            // Kita set null dulu, karena ID ini akan diisi manual oleh Seeder
            // agar relasinya nyambung antara soal dan asesi tertentu.
            'id_soal_ia06'              => null,
            'id_data_sertifikasi_asesi' => null,

            // Jawaban simulasi dari asesi
            'jawaban_asesi'             => $this->faker->paragraph(),

            // Simulasi nilai (0 = Tidak Kompeten, 1 = Kompeten)
            'pencapaian'                => $this->faker->randomElement([0, 1]),
        ];
    }
}