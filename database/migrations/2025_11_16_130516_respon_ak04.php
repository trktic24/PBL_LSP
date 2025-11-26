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
        Schema::create('respon_ak04', function (Blueprint $table) {
            // PK
            $table->id('id_respon_ak04');

            // FK ke Data Sertifikasi (Siapa yang banding?)
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'ak04_sertifikasi_fk')
                  ->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // --- 3 PERTANYAAN UTAMA (UBAH JADI ENUM YA/TIDAK) ---
            
            // 1. Apakah Proses Banding telah dijelaskan kepada Anda?
            $table->enum('penjelasan_banding', ['ya', 'tidak'])->nullable()
                  ->comment('Apakah proses banding telah dijelaskan? (ya/tidak)');

            // 2. Apakah Anda telah mendiskusikan Banding dengan Asesor?
            $table->enum('diskusi_dengan_asesor', ['ya', 'tidak'])->nullable()
                  ->comment('Apakah telah diskusi dengan asesor? (ya/tidak)');

            // 3. Apakah Anda mau melibatkan "orang lain" membantu Anda?
            $table->enum('melibatkan_orang_lain', ['ya', 'tidak'])->nullable()
                  ->comment('Apakah melibatkan orang lain? (ya/tidak)');

            // --- ISIAN ALASAN BANDING ---
            $table->text('alasan_banding')->nullable()->comment('Alasan mengajukan banding');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_ak04');
    }
};