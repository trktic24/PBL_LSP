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

<<<<<<<< HEAD:database/migrations/2025_11_12_142320_ia07.php
            // Isi kolom lainnya sesuai kebutuhan
            $table->text('pertanyaan');
            $table->text('jawaban_asesi')->nullable()->unique();
            $table->text('jawaban_diharapkan');
            $table->boolean('pencapaian')->default(null)->comment('1 untuk Ya, 0 untuk Tidak');
========
            // isi model DB 
            $table->enum('status_kelengkapan', ['memenuhi', 'tidak_memenuhi', 'tidak_ada']);
            $table->string('bukti_kelengkapan')->comment('Sertakan dokumen');
            $table->boolean('status_validasi')->default(false);
>>>>>>>> asesor-auth:database/migrations/2025_11_03_005540_bukti_kelengkapan.php
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
