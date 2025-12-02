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
        Schema::create('master_pertanyaan_lisan', function (Blueprint $table) {
    $table->id('id_pertanyaan_lisan');
    // Relasi ke Unit Kompetensi (PENTING: Sesuaikan nama tabel & kolom referensinya)
    $table->foreignId('id_unit_kompetensi')
          ->constrained('master_unit_kompetensi', 'id_unit_kompetensi')
          ->onDelete('cascade');
    $table->text('pertanyaan');
    $table->text('kunci_jawaban');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_pertanyaan_lisan');
    }
};