<?php

namespace Database\Factories;

// Import Model yang dibutuhkan
use App\Models\ResponBuktiAk01;
use App\Models\DataSertifikasiAsesi;
use App\Models\BuktiAk01;
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
        // 1. Ambil satu Master Bukti secara acak dari database
        // (Pastikan BuktiAk01Seeder sudah dijalankan sebelumnya)
        $buktiMaster = BuktiAk01::inRandomOrder()->first();

        // Jaga-jaga kalau tabel master kosong, kita buat satu dummy
        if (!$buktiMaster) {
            $buktiMaster = BuktiAk01::create(['bukti' => 'Bukti Dummy Factory']);
        }

        return [
            // 2. Bikin Data Sertifikasi Baru (Induknya)
            // Ini otomatis bikin 1 pendaftaran asesi
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),

            // 3. Pakai ID dari Master Bukti yang kita ambil tadi
            'id_bukti_ak01' => $buktiMaster->id_bukti_ak01,

            // 4. Isi kolom 'respon' (keterangan tambahan)
            // optional(0.3) artinya 30% kemungkinan ada isinya, 70% null.
            // Ini simulasi kalau user pilih "Lainnya" dan ngetik sesuatu.
            'respon' => $this->faker->optional(0.3)->sentence(3), 
        ];
    }
}
