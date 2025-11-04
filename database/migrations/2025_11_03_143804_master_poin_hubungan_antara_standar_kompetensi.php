<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Nama tabel PERSIS kayak ERD
        Schema::create('poin_hubungan_antara_standar_kompetensi', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_hubungan_antara_standar_kompetensi');

            // --- INI PERBAIKAN LOGIKANYA ---
            // Pilihan-pilihan ('Bukti...', 'Aktivitas...') kita simpen
            // sebagai BARIS DATA, bukan KOLOM.
            $table->text('pilihan');
            
            $table->timestamps();
        });
    }

    /**
     * Balikkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('poin_hubungan_antara_standar_kompetensi');
    }
};
