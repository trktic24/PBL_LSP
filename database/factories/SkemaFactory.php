<?php

namespace Database\Factories;

use App\Models\Skema; 
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skema>
 */
class SkemaFactory extends Factory
{
    /**
     * 2. WAJIB: Tentukan Model yang dipake
     */
    protected $model = Skema::class;

    /**
     * 3. Definisikan data palsunya
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $nomorGambar = fake()->numberBetween(1, 12);
        return [
            // Bikin kode unit palsu yg mirip aslinya
            'kode_unit' => 'J.' . fake()->numerify('620100.0##.0#'),
            
            
            // Bikin nama skema yg realistis
            'nama_skema' => 'Skema Sertifikasi ' . fake()->randomElement([
                'Junior Web Developer',
                'Digital Marketing',
                'Desain Grafis Muda',
                'Network Administrator',
                'Cloud Practitioner',
                'Data Analyst'
            ]),
            
            // Bikin deskripsi
            'deskripsi_skema' => fake('id_ID')->paragraph(3),
            
            // Bikin nama file PDF palsu
            'SKKNI' => fake()->slug(3) . '_skkni.pdf',

            'gambar' => 'skema' . $nomorGambar . '.jpg'
        ];
    }
}
