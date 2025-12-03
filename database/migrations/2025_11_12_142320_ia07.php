<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ia07', function (Blueprint $table) {
            $table->id('id_ia07');
            
            // 1. Relasi ke Data Sertifikasi Asesi
            // Perhatikan: table name = 'data_sertifikasi_asesi', PK = 'id_data_sertifikasi_asesi'
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onDelete('cascade');

            // 2. Relasi ke Master Pertanyaan
            // Perhatikan: table name = 'master_pertanyaan_lisan', PK = 'id_pertanyaan_lisan'
            $table->foreignId('id_pertanyaan_lisan')
                  ->constrained('master_pertanyaan_lisan', 'id_pertanyaan_lisan')
                  ->onDelete('cascade');

            // 3. Kolom Jawaban
            $table->text('jawaban_asesi')->nullable(); 
            
            // Rekomendasi (Kompeten/Belum Kompeten), default null dulu
            $table->enum('rekomendasi', ['K', 'BK'])->nullable(); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ia07');
    }
};