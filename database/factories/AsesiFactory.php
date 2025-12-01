<?php

namespace Database\Factories;

use App\Models\Asesi;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\DataPekerjaanAsesi;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesi>
 */

class AsesiFactory extends Factory
{
    protected $model = Asesi::class;

    public function definition():array
    {
        // Buat User baru dengan role_id 3 (Asesi)
        $user = User::factory()->create(['role_id' => 3]); 
        
        $gender_string = $this->faker->randomElement(['Laki-laki', 'Perempuan']);
        $fullName = $this->faker->firstName($gender_string) . ' ' . $this->faker->lastName();
        
        return [
            'id_user'        => $user->id_user, 
            'nama_lengkap'   => $fullName,
            'nik'            => $this->faker->unique()->numerify('################'),
            'tempat_lahir'   => $this->faker->city(),
            'tanggal_lahir'  => $this->faker->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
            'jenis_kelamin'  => $gender_string, // Sesuai dengan Enum
            'kebangsaan'     => 'Indonesia',
            'pendidikan'     => $this->faker->randomElement(['D3', 'D4', 'S1']),
            'pekerjaan'      => $this->faker->randomElement(['Mahasiswa', 'Staf IT', 'Admin']),
            'alamat_rumah'   => $this->faker->address(),
            'kode_pos'       => $this->faker->postcode(),
            'kabupaten_kota' => $this->faker->city(),
            'provinsi'       => $this->faker->state(),
            'nomor_hp'       => $this->faker->numerify('082#########'),
            'tanda_tangan'   => 'images/kelengkapan_asesi/tanda_tangan/dummy_ttd.png',
        ];
    }

    /**
     * Konfigurasi factory hook.
     * Method ini akan dijalankan SETELAH Asesi berhasil dibuat.
     */
    public function configure()
    {
        return $this->afterCreating(function (Asesi $asesi) {
            // Buat 1 data pekerjaan untuk asesi ini
            DataPekerjaanAsesi::factory()->create([
                'id_asesi' => $asesi->id_asesi,
            ]);
        });
    }

}
