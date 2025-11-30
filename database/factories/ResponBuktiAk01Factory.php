<?php

namespace Database\Factories;

use App\Models\BuktiAk01;
use App\Models\DataSertifikasiAsesi;
use App\Models\ResponBuktiAk01;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ResponBuktiAk01>
 */
class ResponBuktiAk01Factory extends Factory
{
    protected $model = ResponBuktiAk01::class;    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // OPSI 1: Bukti tetap buat baru atau ambil acak
            'id_bukti_ak01' => BuktiAk01::inRandomOrder()->first()?->id_bukti_ak01 ?? BuktiAk01::factory(),

            // OPSI 2 (PERBAIKAN): 
            // Coba ambil satu ID acak dari tabel data_sertifikasi_asesi yang SUDAH ADA.
            // Jika tabel kosong (null), baru jalankan factory().
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::inRandomOrder()->first()?->id_data_sertifikasi_asesi ?? DataSertifikasiAsesi::factory(),
            
            'respon' => $this->faker->randomElement(['Memenuhi', 'Tidak Memenuhi', 'Valid']),
        ];
    }
}
