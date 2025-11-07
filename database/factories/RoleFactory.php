<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Role;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            // Nama role unik, acak jika dipakai untuk testing tambahan
            'nama_role' => fake()->unique()->randomElement([
                'asesi', 'asesor', 'admin', 'master asesor', 'superadmin',
            ]),
        ];
    }
}
