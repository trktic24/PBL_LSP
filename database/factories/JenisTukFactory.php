<?php

namespace Database\Factories;

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
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sewaktu' => $this->faker->sentence(4),
            'tempat_kerja' => $this->faker->sentence(4),
            'mandiri' => $this->faker->sentence(4),
        ];
    }
}
