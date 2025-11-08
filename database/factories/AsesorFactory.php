<?php

namespace Database\Factories;

use App\Models\Asesor;
use App\Models\User;
use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsesorFactory extends Factory
{
    protected $model = Asesor::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->asesor(),
            
            'nomor_regis' => 'REG-' . fake()->unique()->numerify('#####'),
            'nama_lengkap' => fake()->name(),
            
            'nik' => fake()->unique()->numerify('################'), // 16 digit

            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            
            'kebangsaan' => 'Indonesia',
            'pekerjaan' => fake()->jobTitle(),
            'alamat_rumah' => fake()->address(),
            'kode_pos' => fake()->postcode(),
            'kabupaten_kota' => fake()->city(),
            'provinsi' => fake()->state(),
            'nomor_hp' => fake()->numerify('081#########'),
            'NPWP' => fake()->numerify('###############'),
            'nama_bank' => 'BCA',
            'norek' => fake()->numerify('##########'), 
            
            'ktp' => 'dummy/ktp.pdf',
            'pas_foto' => 'dummy/foto.png',
            'NPWP_foto' => 'dummy/npwp.pdf',
            'rekening_foto' => 'dummy/rekening.pdf',
            'CV' => 'dummy/cv.pdf',
            'ijazah' => 'dummy/ijazah.pdf',
            'sertifikat_asesor' => 'dummy/sert_asesor.pdf',
            'sertifikasi_kompetensi' => 'dummy/sert_kompetensi.pdf',
            'tanda_tangan' => 'dummy/ttd.png',
            
            'is_verified' => true,
        ];
    }
}