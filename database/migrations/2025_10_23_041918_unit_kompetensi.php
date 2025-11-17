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
        Schema::create('unit_kompetensi', function (Blueprint $table) {
            $table->id('id_unit_kompetensi');
            $table->foreignId('id_kelompok_pekerjaan')->constrained('kelompok_pekerjaan', 'id_kelompok_pekerjaan')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom sesuai ERD
            $table->string('kode_unit')->unique();
            $table->string('judul_unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_kompetensi');
    }
};
