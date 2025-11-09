<?php

namespace Database\Factories;
use App\Models\DataPekerjaanAsesi;
use App\Models\Asesi; 
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataPekerjaanAsesi>
 */
class DataPekerjaanAsesiFactory extends Factory
{
    /**
     * Model yang digunakan oleh factory ini.
     *
     * @var string
     */
    protected $model = DataPekerjaanAsesi::class;

    /**
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Foreign Key ke tabel 'asesi'. Menggunakan factory Asesi untuk memastikan Asesi dibuat.
            // Asumsi: foreign key di Model Asesi adalah 'id_asesi'
            'id_asesi' => Asesi::factory()->create(['id_asesi' => null])->id_asesi,

            // Data pekerjaan
            'nama_institusi_pekerjaan' => fake()->company(),
            'alamat_institusi' => fake()->address(),
            'jabatan' => fake()->randomElement([
                'Software Engineer',
                'Junior Developer',
                'System Administrator',
                'UX Designer',
                'Network Technician',
                'Web Designer',
                'IT Support'
            ]),
            'kode_pos_institusi' => fake()->postcode(),
            'no_telepon_institusi' => fake()->numerify('08##########'),
        ];
    }

    /**
     * State opsional untuk mengaitkan ke Asesi tertentu.
     */
    public function forAsesi(int $asesiId): static
    {
        return $this->state(fn(array $attributes) => [
            'id_asesi' => $asesiId,
        ]);
    }
}
