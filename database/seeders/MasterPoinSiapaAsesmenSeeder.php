<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MasterPoinSiapaAsesmenSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('master_poin_siapa_melakukan_asesmen')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('master_poin_siapa_melakukan_asesmen')->insert([
            ['pilihan' => 'Lembaga Sertifikasi', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Organisasi Pelatihan', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Asesor Perusahaan', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}