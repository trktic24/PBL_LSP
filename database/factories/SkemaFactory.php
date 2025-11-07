<?php

namespace Database\Factories;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Skema;
=======
use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
>>>>>>> origin/kelompok_1

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skema>
 */
class SkemaFactory extends Factory
{
    /**
<<<<<<< HEAD
     * Tentukan model yang terhubung dengan factory ini.
=======
     * The name of the factory's corresponding model.
>>>>>>> origin/kelompok_1
     *
     * @var string
     */
    protected $model = Skema::class;

    /**
<<<<<<< HEAD
     * Definisikan status default model.
=======
     * Define the model's default state.
>>>>>>> origin/kelompok_1
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
<<<<<<< HEAD
            // Membuat kode unit yang terlihat realistis
            'kode_unit' => fake()->numerify('J.620100.###.##'), 
            
            // Memilih nama skema yang umum dari daftar
            'nama_skema' => fake()->randomElement([
                'Junior Web Developer',
                'Ahli Digital Marketing',
                'Operator Komputer Madya',
                'Desainer Grafis Muda',
                'Network Administrator',
                'Data Analyst',
            ]),

            // Membuat deskripsi palsu (3 kalimat)
            'deskripsi_skema' => fake()->paragraph(3, true),

            // Path dummy untuk file PDF
            'SKKNI' => '/storage/files/dummy_skkni.pdf',

            // URL gambar placeholder yang nyata
            'gambar' => fake()->imageUrl(640, 480, 'technology', true), 
        ];
    }
}
=======
            // Contoh: J.620100.001.01
            'kode_unit' => 'J.' . $this->faker->numberBetween(100000, 999999) . '.' . $this->faker->numberBetween(100, 999) . '.01',
            'nama_skema' => 'Skema Sertifikasi ' . $this->faker->jobTitle(),
            'deskripsi_skema' => $this->faker->paragraph(2), // Deskripsi 2 paragraf

            // Sesuai permintaan lu, ini di-null-kan
            'SKKNI' => null,
            'gambar' => null,
        ];
    }
}
>>>>>>> origin/kelompok_1
