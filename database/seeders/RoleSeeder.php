<?php

namespace Database\Seeders;

<<<<<<< HEAD
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
=======
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // Import model Role
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // 2. Matikan dulu 'aturan' foreign key
        Schema::disableForeignKeyConstraints();

        // 2. Hapus data lama (biar gak numpuk kalo di-seed ulang)
        DB::table('roles')->truncate();

        // 4. Nyalakan lagi 'aturan'-nya (PENTING!)
        Schema::enableForeignKeyConstraints();

        // 3. Masukkan data baru
        DB::table('roles')->insert([
            [
                'nama_role' => 'asesi',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'asesor',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'master admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
=======
        Role::updateOrCreate(['nama_role' => 'admin']);
        Role::updateOrCreate(['nama_role' => 'asesi']);
        Role::updateOrCreate(['nama_role' => 'asesor']);
        Role::updateOrCreate(['nama_role' => 'superadmin']);
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
    }
}
