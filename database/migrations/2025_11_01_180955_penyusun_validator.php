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
        Schema::create('penyusun_validator', function (Blueprint $table) {
            $table->id('id_penyusun_validator');
            // --- KUNCI 1: Sambung ke 'penyusun' ---
            $table->foreignId('id_penyusun')->constrained('penyusun', 'id_penyusun')->onUpdate('cascade')->onDelete('cascade');

            // --- KUNCI 2: Sambung ke 'validator' ---
            $table->foreignId('id_validator')->constrained('validator', 'id_validator')->onUpdate('cascade')->onDelete('cascade');

            // --- KUNCI 3: Sambung ke 'kelompok_pekerjaans' ---
            // (Ingat, nama tabel lu pake 's' di migrasi sebelumnya)
            $table->foreignId('id_kelompok_pekerjaan')->constrained('kelompok_pekerjaans', 'id_kelompok_pekerjaan')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisanya (sesuai ERD)
            $table->date('tanggal_validasi'); // Cukup 'date' kalo cuma tanggal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyusun_validator');
    }
};
