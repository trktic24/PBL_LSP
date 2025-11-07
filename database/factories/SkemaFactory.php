<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Skema;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skema>
 */
class SkemaFactory extends Factory
{
    /**
     * Tentukan model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = \App\Models\Skema::class;

    /**
     * Definisikan status default model.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
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