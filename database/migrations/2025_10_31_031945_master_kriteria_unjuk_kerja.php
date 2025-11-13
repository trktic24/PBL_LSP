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
        Schema::create('master_kriteria_unjuk_kerja', function (Blueprint $table) {
            $table->id('id_kriteria');
            $table->foreignId('id_elemen')->constrained('master_elemen', 'id_elemen')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisanya (sesuai ERD)
            $table->string('standar_industri_kerja');

            // 'kriteria' juga pakai text() buat deskripsi panjang
            $table->string('kriteria');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_kriteria_unjuk_kerja');
    }
};
