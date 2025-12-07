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
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // 3. Kolom Jawaban
            $table->text('pertanyaan')->nullable(); 
            $table->text('jawaban_asesi')->nullable(); 
            $table->text('jawaban_yang_diharapkan')->nullable(); 
            $table->enum('pencapaian', ['ya', 'tidak'])->nullable()->comment('Apakah jawaban asesi sesuai dengan yang diharapkan?');
            
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ia07');
    }
};