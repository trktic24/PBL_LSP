<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Ambil ID role 'asesi' dari DB (ini butuh RoleSeeder udah jalan)
        $asesiRoleId = Role::where('nama_role', 'asesi')->first()->id_role ?? 1;

        return [
            // GANTI 'name' JADI 'username'
            'username' => fake()->unique()->userName(),

            'email' => fake()->unique()->safeEmail(),
            'password' => (static::$password ??= Hash::make('password')),

            // TAMBAHIN 'role_id'
            'role_id' => $asesiRoleId,

            'google_id' => null,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }
    public function unverified(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'email_verified_at' => null,
            ],
        );
    }
}
