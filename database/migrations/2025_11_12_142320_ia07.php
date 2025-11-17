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
        Schema::create('ia07', function (Blueprint $table) {
            $table->id('id_ia07');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // Isi kolom lainnya sesuai kebutuhan
            $table->text('pertanyaan');
            $table->text('jawaban_asesi')->nullable()->unique();
            $table->text('jawaban_diharapkan');
            $table->boolean('pencapaian')->default(null)->comment('1 untuk Ya, 0 untuk Tidak');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Scheama::dropIfExists('ia07');
    }
};
