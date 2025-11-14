<?php

namespace Database\Factories;

<<<<<<< HEAD
use App\Models\JenisTuk;
use Illuminate\Database\Eloquent\Factories\Factory;

class JenisTukFactory extends Factory
{
    /**
     * Model yang terhubung dengan factory ini.
     *
     * @var string
     */
    protected $model = JenisTuk::class;

    /**
     * Mendefinisikan status default model.
=======
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\JenisTuk>
 */
class JenisTukFactory extends Factory
{
    /**
     * Define the model's default state.
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
<<<<<<< HEAD
        return [
            'sewaktu' => $this->faker->sentence(4),
            'tempat_kerja' => $this->faker->sentence(4),
            'mandiri' => $this->faker->sentence(4),
        ];
    }
}
=======
        // Migrasi Anda menggunakan string. Jika ini seharusnya
        // merepresentasikan 'Ya'/'Tidak', Anda bisa ganti faker->word()
        // dengan ->faker->randomElement(['Ya', 'Tidak']).
        // Saya asumsikan ini adalah string deskriptif singkat.
        return [
            'sewaktu' => $this->faker->sentence(2),
            'tempat_kerja' => $this->faker->sentence(2),
            'mandiri' => $this->faker->sentence(2),
        ];
    }
}
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
