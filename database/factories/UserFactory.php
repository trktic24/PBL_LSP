<?php

namespace Database\Factories;

use App\Models\Role; // <-- Import Role
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; // <-- Import Hash
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Ambil role_id secara acak dari tabel roles
            // Ini ASUMSI tabel roles-nya udah keisi dulu
            'role_id' => Role::inRandomOrder()->first()->id_role,
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),

            // Password default-nya adalah 'password'
            'password' => Hash::make('password'),

            'google_id' => null,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * State khusus untuk membuat Asesor (role_id = 3)
     * (Ini akan dipanggil oleh AsesorFactory)
     */
    public function asesor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => 3,
        ]);
    }
}