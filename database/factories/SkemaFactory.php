<?php

namespace Database\Factories;

use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;

class SkemaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Skema::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_unit' => 'J.' . $this->faker->numberBetween(100000, 999999) . '.' . $this->faker->numberBetween(100, 999) . '.01',
            'nama_skema' => 'Skema Sertifikasi ' . $this->faker->randomElement([
                'Network Engineer',
                'Software Developer',
                'Database Administrator',
                'UI/UX Designer',
                'Cybersecurity Analyst',
                'Cloud Specialist',
                'AI Engineer',
                'Data Scientist',
                'IT Support Technician',
                'Hardware Engineer',
            ]),
            'deskripsi_skema' => $this->faker->paragraph(2),
            
            // Field opsional (boleh null)
            'SKKNI' => null,
            'gambar' => null,

            // Field baru hasil migrasi
            'harga' => $this->faker->numberBetween(100000, 1000000), // harga antara 100 ribu - 1 juta
            'kategori' => $this->faker->randomElement([
                'Software',
                'Hardware',
                'Network',
                'Keamanan',
                'Cloud',
                'AI',
                'Data',
                'UI/UX',
            ]),
        ];
    }
}
