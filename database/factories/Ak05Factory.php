<?php

namespace Database\Factories;

use App\Models\Ak05;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ak05>
 */
class Ak05Factory extends Factory
{
/**
     * Nama model yang sesuai dengan factory ini.
     *
     * @var string
     */
    protected $model = Ak05::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Mendapatkan instance Faker
        $faker = $this->faker;

        // Pilihan untuk kolom saran_perbaikan (sesuai comment di migration)
        $saranPilihan = ['Asesor', 'Personil Terkait'];  

        return [
            // id_ak05 akan otomatis diisi karena merupakan primary key dan auto-increment

            // isi kolom tabel ak05
            'aspek_negatif_positif' => $faker->optional(0.8)->text(500), // 80% kemungkinan diisi, 20% null
            'penolakan_hasil_asesmen' => $faker->optional(0.6)->text(300), // 60% kemungkinan diisi, 40% null
            
            // Menggunakan element acak dari array $saranPilihan atau null
            'saran_perbaikan' => $faker->optional(0.7)->randomElement($saranPilihan), 
            
            // created_at dan updated_at akan otomatis diisi oleh Laravel (timestamps)
        ];
    }


}
