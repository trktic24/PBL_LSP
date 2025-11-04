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
        Schema::create('respon_siapa_melakukan_asesmen_mapa01', function (Blueprint $table) {
            $table->id('id_respon_siapa_melakukan_asesmen_mapa01');
            
            // --- PERBAIKAN FK 1 (ke asesi) ---
            // 1. Bikin kolomnya dulu
            $table->foreignId('id_data_sertifikasi_asesi');
            // 2. Bikin constraint-nya DENGAN NAMA PENDEK
            $table->foreign('id_data_sertifikasi_asesi', 'resp_mapa01_sertifikasi_fk') // <- NAMA PENDEK
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- PERBAIKAN FK 2 (ke master siapa) ---
            $table->foreignId('id_siapa_melakukan_asesmen');
            $table->foreign('id_siapa_melakukan_asesmen', 'resp_mapa01_siapa_fk') // <- NAMA PENDEK
                  ->references('id_siapa_melakukan_asesmen')->on('master_poin_siapa_melakukan_asesmen')
                  ->onUpdate('cascade')->onDelete('cascade');
            
            // --- PERBAIKAN FK 3 (ke master hubungan) ---
            $table->foreignId('id_hubungan_antara_standar_kompetensi');
            $table->foreign('id_hubungan_antara_standar_kompetensi', 'resp_mapa01_hubungan_fk') // <- NAMA PENDEK
                  ->references('id_hubungan_antara_standar_kompetensi')->on('poin_hubungan_antara_standar_kompetensi')
                  ->onUpdate('cascade')->onDelete('cascade');
            
            $table->text('Respon_asesor')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_siapa_melakukan_asesmen_mapa01');
    }
};
