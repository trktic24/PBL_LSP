<?php

namespace Database\Factories;

<<<<<<< HEAD
use App\Models\Skema; 
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Skema>
 */
class SkemaFactory extends Factory
{
    /**
     * 2. WAJIB: Tentukan Model yang dipake
=======
use App\Models\Skema;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class SkemaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
     */
    protected $model = Skema::class;

    /**
<<<<<<< HEAD
     * 3. Definisikan data palsunya
=======
     * Define the model's default state.
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
<<<<<<< HEAD
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

            'gambar' => null
        ];
    }
}
=======
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
        $categoryIds = Category::pluck('id');

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
            'category_id' => $this->faker->randomElement($categoryIds),
        ];
    }
}
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
