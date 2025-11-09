<?php

namespace Database\Factories;
use App\Models\DataPekerjaanAsesi;
use App\Models\Asesi; // Pastikan Model Asesi sudah di-import
use App\Models\DataPekerjaanAsesi; // Pastiin nama model lu bener
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
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Foreign Key ke tabel 'asesi'. Menggunakan factory Asesi untuk memastikan Asesi dibuat.
            // Asumsi: foreign key di Model Asesi adalah 'id_asesi'
            'id_asesi' => Asesi::factory()->create(['id_asesi' => null])->id_asesi,

            // Data Pekerjaan Sekarang
            'nama_institusi_pekerjaan' => fake()->company(),
            'alamat_institusi' => fake()->address(),
            'jabatan' => fake()->randomElement(['Software Engineer', 'Junior Developer', 'System Administrator', 'UX Designer', 'Network Technician', 'Web Designer', 'IT Support']),
            // Menggunakan postcode() untuk kode pos
            'kode_pos_institusi' => fake()->postcode(),
            // Menggunakan numerify() untuk nomor telepon yang formatnya sesuai
            'no_telepon_institusi' => fake()->numerify('08##########'),

            // Kolom timestamps akan diisi otomatis
        ];
    }

    /**
     * Definisikan state untuk mengaitkan Pekerjaan dengan Asesi yang sudah ada.
     */
    public function forAsesi(int $asesiId): static
    {
        return $this->state(
            fn(array $attributes) => [
                'id_asesi' => $asesiId,
            ],
        );
    }
}
            
            // Ini ngisi kolom sesuai migrasi lu
            'id_asesi' => \App\Models\Asesi::factory(),
            'nama_institusi_pekerjaan' => fake()->company(),
            'alamat_institusi'         => fake()->address(),
            'jabatan'                  => fake()->jobTitle(),
            'kode_pos_institusi'       => fake()->postcode(),
            'no_telepon_institusi'     => fake()->numerify('081#########'), 
        ];
    }
}