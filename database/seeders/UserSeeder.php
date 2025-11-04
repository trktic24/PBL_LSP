<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // Gunakan Hash Facade

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'role_id' => 1,  // Ini akan berhasil karena RoleSeeder berjalan duluan
            'username' => 'admin',
            'email' => 'admin@polines.ac.id',
            'password' => Hash::make('1234') // bcrypt() juga bisa, tapi Hash lebih modern
        ]);
    }
}