<?php

namespace Database\Factories; // WAJIB HARUS Database\Factories

use App\Models\Asesi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AsesiFactory extends Factory // WAJIB HARUS AsesiFactory
{
    protected $model = Asesi::class;

    public function definition():array
    {
        // ... (Logika Factory Anda yang sudah benar)
        $user = User::factory()->create(['role_id' => 3]); 
        
        $gender = $this->faker->randomElement(['Laki-laki', 'Perempuan']);
        $fullName = $this->faker->firstName($gender) . ' ' . $this->faker->lastName();
        
        return [
            'id_user'        => $user->id_user, 
            'nama_lengkap'   => $fullName,
            'nik'            => $this->faker->unique()->numerify('################'),
            'tempat_lahir'   => $this->faker->city(),
            'tanggal_lahir'  => $this->faker->dateTimeBetween('-25 years', '-18 years')->format('Y-m-d'),
            'jenis_kelamin'  => $gender,
            'kebangsaan'     => 'Indonesia',
            'pendidikan'     => $this->faker->randomElement(['D3 Teknologi Komputer', 'D4 Sistem Informasi', 'S1 Teknik Elektro']),
            'pekerjaan'      => $this->faker->randomElement(['Mahasiswa', 'Staf IT', 'Admin Kantor']),
            'alamat_rumah'   => $this->faker->address(),
            'kode_pos'       => $this->faker->postcode(),
            'kabupaten_kota' => $this->faker->city(),
            'provinsi'       => $this->faker->state(),
            'nomor_hp'       => $this->faker->numerify('082#########'),
            'tanda_tangan'   => null,
        ];
    }
}