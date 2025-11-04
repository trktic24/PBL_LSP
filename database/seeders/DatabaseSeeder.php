<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Asesor; 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Panggil seeder Role dan User (Admin)
        $this->call([
            RoleSeeder::class, // Membuat role 1, 2, 3
            UserSeeder::class, // Membuat 1 user 'admin' (role_id 1)
        ]);

        // Buat 10 data Asesor dummy
        // Ini akan otomatis memanggil AsesorFactory,
        // yang juga akan otomatis memanggil UserFactory (untuk role_id 2)
        // dan SkemaFactory.
        Asesor::factory(10)->create(); 
    }
}