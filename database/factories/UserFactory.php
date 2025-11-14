<?php

namespace Database\Factories;

<<<<<<< HEAD
use App\Models\Role;
=======
use App\Models\Role; // <-- Import Role
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash; // <-- Import Hash
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
<<<<<<< HEAD
    protected static ?string $password;

=======
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
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
<<<<<<< HEAD
            // GANTI 'name' JADI 'username'
            'username' => fake()->unique()->userName(),

            'email' => fake()->unique()->safeEmail(),
            'password' => (static::$password ??= Hash::make('password')),

            // TAMBAHIN 'role_id'
            'role_id' => $asesiRoleId,

            'google_id' => null,
            'email_verified_at' => now(),
=======
            // Ambil role_id secara acak dari tabel roles
            // Ini ASUMSI tabel roles-nya udah keisi dulu
            'role_id' => Role::inRandomOrder()->first()->id_role,

            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),

            // Password default-nya adalah 'password'
            'password' => Hash::make('password'),

            'google_id' => null,
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
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
