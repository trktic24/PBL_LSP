<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('standar_industri_mapa01', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_standar_industri_mapa01');

            // --- INI PERBAIKAN LOGIKANYA ---
            // Pilihan-pilihan ('Standar Kompetensi...', 'Kriteria asesmen...')
            // kita simpen sebagai BARIS DATA, bukan KOLOM.
            $table->text('pilihan');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('standar_industri_mapa01');
    }
};
