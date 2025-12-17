<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'id_user' => null, // akan diisi oleh AdminSeeder

            'nama_admin' => $this->faker->name(),

            'tanda_tangan_admin' => 'images/tanda_tangan/admin_dummy.png',
        ];
    }
}
