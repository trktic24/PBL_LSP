<?php

namespace Database\Factories;

use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class SkemaFactory extends Factory
{
    protected $model = Skema::class;

    public function definition(): array
    {
        $categoryIds = Category::pluck('id')->all();
        if (empty($categoryIds)) {
            $categoryIds = [1];
        }

        // pilih salah satu gambar skema1.jpg - skema10.jpg
        $gambar = 'skema/skema' . $this->faker->numberBetween(1, 10) . '.jpg';

        return [
            'kode_unit' => 'J.' . $this->faker->numberBetween(100000, 999999) . '.' .
                           $this->faker->numberBetween(100, 999) . '.01',

            'nama_skema' => 'Skema Sertifikasi ' . $this->faker->randomElement([
                'Network Engineer', 'Software Developer', 'Database Administrator',
                'UI/UX Designer', 'Cybersecurity Analyst', 'Cloud Specialist',
                'AI Engineer', 'Data Scientist', 'IT Support Technician',
                'Hardware Engineer'
            ]),

            'deskripsi_skema' => $this->faker->paragraph(2),
            'SKKNI' => null,

            // INI YANG PENTING
            'gambar' => 'skema' . rand(1, 10) . '.jpg',

            'harga' => $this->faker->numberBetween(100000, 1000000),
            'category_id' => $this->faker->randomElement($categoryIds),
        ];
    }
}
