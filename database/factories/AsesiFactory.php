<?php

namespace Database\Factories;

use App\Models\Asesi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Asesi>
 */
class AsesiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Asesi::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // id_user akan kita isi dari Seeder

        return [
            'nama_lengkap' => $this->faker->name(),
            'nik' => $this->faker->unique()->numerify('################'), // 16 digit
            'tempat_lahir' => $this->faker->city(),
            'tanggal_lahir' => $this->faker->date('Y-m-d', '2003-01-01'),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'kebangsaan' => 'Indonesia',
            'pendidikan' => $this->faker->randomElement(['SMA/SMK', 'D3', 'S1', 'S2']),
            'pekerjaan' => $this->faker->jobTitle(),
            'alamat_rumah' => $this->faker->address(),
            'kode_pos' => $this->faker->postcode(),
            'kabupaten_kota' => $this->faker->city(),
            'provinsi' => $this->faker->state(),
            'nomor_hp' => '08' . $this->faker->numerify('##########'),
            'tanda_tangan' => null, // Sesuai migration, file kita null-kan
        ];
    }
}
