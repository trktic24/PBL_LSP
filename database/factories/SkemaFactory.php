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
        // Daftar Kategori IT yang Anda inginkan
        $categories = [
            'Software Development', 
            'Network & Infrastructure', 
            'Data Science & AI', 
            'Cyber Security', 
            'Cloud Computing', 
            'UI/UX Design',
            'Mobile Development',
            'Database Administration',
            'DevOps & Automation',
            'Game Development',
            'Business Analyst IT',
            'Digital Marketing IT'
        ];

        return [
            // Contoh: J.620100.001.01
            'kode_unit' => 'J.' . $this->faker->numberBetween(100000, 999999) . '.' . $this->faker->numberBetween(100, 999) . '.01',
            'nama_skema' => 'Skema Sertifikasi ' . $this->faker->jobTitle(),
            'deskripsi_skema' => $this->faker->paragraph(2), // Deskripsi 2 paragraf
            
            // Kolom Kategori dengan isi acak
            'category' => $this->faker->randomElement($categories), // Kolom ini akan diisi acak dari array $categories
            
            // Tambahkan kolom 'harga' sesuai permintaan Anda
            'harga' => $this->faker->numberBetween(100000, 5000000), // Harga acak antara 100k - 5jt

            // Sesuai permintaan lu, ini di-null-kan
            'SKKNI' => null,
            'gambar' => null, // Anda bisa mengganti ini dengan path gambar default jika ada
        ];
    }
}