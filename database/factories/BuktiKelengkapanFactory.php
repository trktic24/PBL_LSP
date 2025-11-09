<?php

namespace Database\Factories;

use App\Models\BuktiKelengkapan;
use App\Models\DataSertifikasiAsesi;
use Illuminate\Database\Eloquent\Factories\Factory;

class BuktiKelengkapanFactory extends Factory
{
    protected $model = BuktiKelengkapan::class;

    public function definition(): array
    {
        // Daftar jenis sesuai blade kamu
        $jenisDokumen = [
            'foto', 
            'ijazah', 
            'cv', 
            'ktp_pernyataan', 
            'khs', 
            'sertifikat_polines', 
            'surat_kerja'
        ];

        return [
            'id_data_sertifikasi_asesi' => DataSertifikasiAsesi::factory(),
            'jenis_dokumen' => $this->faker->randomElement($jenisDokumen),
            'keterangan' => $this->faker->sentence(3),
            'bukti_kelengkapan' => 'dokumen/dummy/' . $this->faker->word . '.pdf',
            'status_kelengkapan' => $this->faker->randomElement(['memenuhi', 'tidak_memenuhi', 'tidak_ada']),
        ];
    }
}