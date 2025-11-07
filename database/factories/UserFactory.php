<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    public function definition(): array
    {
        return [
            'role_id' => 2, // default: 2 (misalnya untuk asesor, nanti bisa disesuaikan)
            'username' => $this->faker->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'), // password default
            'google_id' => null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
}
