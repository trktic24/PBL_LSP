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
        Schema::create('ia10', function (Blueprint $table) {
            $table->id('id_ia10');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database ia10
<<<<<<< HEAD
            $table->string('pertanyaan1');
            $table->string('pertanyaan2');
            $table->string('pertanyaan3');
            $table->string('jawaban1');
            $table->string('jawaban2');
            $table->string('jawaban3');
=======
            $table->text('pertanyaan');
            $table->boolean('jawaban_iya')->default(false)->comment('1 untuk Ya, 0 untuk Tidak');
            $table->boolean('jawaban_tidak')->default(false)->comment('1 untuk Tidak, 0 untuk Ya');
>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ia10');
    }
};
