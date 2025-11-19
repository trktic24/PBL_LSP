<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // Import model Role

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::updateOrCreate(['nama_role' => 'admin']);
        Role::updateOrCreate(['nama_role' => 'asesi']);
        Role::updateOrCreate(['nama_role' => 'asesor']);
        Role::updateOrCreate(['nama_role' => 'superadmin']);
    }
}