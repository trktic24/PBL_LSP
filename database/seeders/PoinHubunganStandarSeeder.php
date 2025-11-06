<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PoinHubunganStandarSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('poin_hubungan_antara_standar_kompetensi')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('poin_hubungan_antara_standar_kompetensi')->insert([
            ['pilihan' => 'Bukti untuk mendukung asesmen / RPL', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Aktivitas kerja di tempat kerja kandidat', 'created_at' => now(), 'updated_at' => now()],
            ['pilihan' => 'Kegiatan Pembelajaran', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}