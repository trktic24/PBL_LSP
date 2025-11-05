<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Jadwal;
use App\Models\JenisTuk;
use App\Models\Tuk;
use App\Models\Skema;
use App\Models\Asesor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Jadwal>
 */
class JadwalFactory extends Factory
{
    /**
     * Tentukan model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = Jadwal::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Logika untuk membuat urutan tanggal yang masuk akal
        // 1. Tentukan tanggal mulai pendaftaran (misal: 1-2 minggu dari sekarang)
        $mulai = fake()->dateTimeBetween('+1 week', '+2 weeks');
        
        // 2. Tentukan tanggal selesai pendaftaran (misal: 5-10 hari setelah pendaftaran dibuka)
        $selesai = fake()->dateTimeBetween($mulai, (clone $mulai)->modify('+10 days'));
        
        // 3. Tentukan tanggal pelaksanaan (misal: 1-7 hari setelah pendaftaran ditutup)
        $pelaksanaan = fake()->dateTimeBetween((clone $selesai)->modify('+1 day'), (clone $selesai)->modify('+1 week'));

        return [
            // --- Foreign Keys ---
            // Otomatis membuat model baru untuk setiap relasi
            'id_jenis_tuk' => JenisTuk::factory(),
            'id_tuk' => Tuk::factory(),
            'id_skema' => Skema::factory(),
            'id_asesor' => Asesor::factory(),

            // --- Isi Kolom ---
            'sesi' => fake()->numberBetween(1, 3), // Asumsi ada 1-3 sesi
            
            // --- Tanggal (sesuai urutan) ---
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => $selesai,
            'tanggal_pelaksanaan' => $pelaksanaan,

            // --- Status ---
            'Status_jadwal' => fake()->randomElement(['Terjadwal', 'Selesai', 'Dibatalkan']),
        ];
    }
}