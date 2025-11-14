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
        Schema::create('ia08', function (Blueprint $table) {
            $table->id('id_ia08');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database ia08
<<<<<<< HEAD
            $table->string('materi_atau_substansi_wawancara');
            $table->string('bukti_tambahan')->comment('Deskripsi bukti tambahan yang diajukan oleh asesi');
=======
            $table->text('materi_atau_substansi_wawancara');
            $table->text('bukti_tambahan')->comment('Deskripsi bukti tambahan yang diajukan oleh asesi');
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
            $table->enum('rekomendasi', ['kompeten', 'perlu observasi langsung']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia08');
    }
};
