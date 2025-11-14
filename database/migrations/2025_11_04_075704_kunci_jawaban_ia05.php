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
        Schema::create('kunci_jawaban_ia05', function (Blueprint $table) {
            $table->id('id_kunci_jawaban_ia05');
            $table->foreignId('id_soal_ia05')->constrained('soal_ia05', 'id_soal_ia05')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database kunci_jawaban_ia05
<<<<<<< HEAD
            $table->string('teks_kunci_jawaban_ia05');
            $table->boolean('is_kunci_jawaban')->default(false);
=======
            $table->integer('nomor_kunci_jawaban_ia05');
            $table->string('teks_kunci_jawaban_ia05');
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunci_jawaban_ia05');
    }
};
