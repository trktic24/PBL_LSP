<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ia08', function (Blueprint $table) {
            $table->id('id_ia08');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database ia08
            $table->text('bukti_tambahan')->nullable()->comment('Deskripsi bukti tambahan yang diajukan oleh asesi');
            $table->enum('rekomendasi', ['kompeten', 'perlu observasi lanjut']);

            // Penambahan kolom untuk observasi lanjut (nullable)
            $table->string('kelompok_pekerjaan')->nullable()->comment('Diisi jika rekomendasi perlu observasi langsung');
            $table->string('unit_kompetensi')->nullable()->comment('Diisi jika rekomendasi perlu observasi langsung');
            $table->string('elemen')->nullable()->comment('Diisi jika rekomendasi perlu observasi langsung');
            $table->string('kuk')->nullable()->comment('Diisi jika rekomendasi perlu observasi langsung');
            
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
