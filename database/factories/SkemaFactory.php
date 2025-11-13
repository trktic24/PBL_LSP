<?php

namespace Database\Factories;

use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

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
        // Ambil semua ID dari tabel categories dan ubah menjadi array
        $categoryIds = Category::pluck('id')->all();

        // Pencegahan error jika tidak ada kategori.
        if (empty($categoryIds)) {
            // Anda bisa mengembalikan ID 1 atau null, tergantung setting database Anda
            // Misalnya, kita kembalikan ID default 1 untuk menghindari crash
            return $this->getDefaultSkemaDefinition();
        }

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

            // Field opsional
            'SKKNI' => null,
            'gambar' => null,

            // Field baru hasil migrasi
            'harga' => $this->faker->numberBetween(100000, 1000000), 
            
            // PENGISIAN RANDOM: Memilih salah satu ID kategori secara acak
            'category_id' => $this->faker->randomElement($categoryIds),
        ];
    }
    
    /**
     * Return a default definition if categories are not yet seeded.
     */
    protected function getDefaultSkemaDefinition(): array
    {
        return [
            'kode_unit' => 'J.' . $this->faker->numberBetween(100000, 999999) . '.' . $this->faker->numberBetween(100, 999) . '.01',
            'nama_skema' => 'Skema Sertifikasi Default',
            'deskripsi_skema' => $this->faker->paragraph(2),
            'SKKNI' => null,
            'gambar' => null,
            'harga' => $this->faker->numberBetween(100000, 1000000), 
            'category_id' => 1, // Default ID jika tidak ada kategori
        ];
    }
}