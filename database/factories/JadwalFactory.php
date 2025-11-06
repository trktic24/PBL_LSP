<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            // Asumsi lo punya kolom 'nama_kegiatan' atau sejenisnya
            'nama_kegiatan' => $this->faker->sentence(4), 
            
            // Asumsi lo punya kolom 'deskripsi'
            'deskripsi' => $this->faker->paragraph(3),

            // INI KUNCINYA
            // Ganti 'tanggal_pelaksanaan' sesuai nama kolom lo
            // Ini akan generate tanggal antara 1 bulan lalu dan 2 bulan ke depan
            'tanggal_pelaksanaan' => $this->faker->dateTimeBetween('-1 month', '+2 months'),

            // Tambahin kolom lain yg 'required' di sini...
            // Misalnya 'skema_id', 'asesor_id', dll.
            // 'skema_id' => \App\Models\Skema::factory(), // Contoh kalau relasi
        ];
    }
}