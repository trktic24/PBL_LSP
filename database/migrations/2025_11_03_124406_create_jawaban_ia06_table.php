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
        Schema::create('jawaban_ia06', function (Blueprint $table) {
            $table->id('id_jawaban_IA06');


            $table->foreignid('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            $table->foreignid('id_soal_IA06')->constrained('soal_IA06', 'id_soal_IA06')->onUpdate('cascade')->onDelete('cascade');

            $table->foreignid('id_kunci_IA06')->constrained('kunci_IA06', 'id_kunci_IA06')->onUpdate('cascade')->onDelete('cascade');

            $table->string('jawbaan_asesi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_ia06');
    }
};
