<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal_ia05', function (Blueprint $table) {
            $table->id('id_soal_ia05'); // Primary Key

            // [PENTING] HUBUNGAN KE SKEMA
            // Asumsi nama tabel skema kamu adalah 'skemas' dan PK-nya 'id_skema'
            $table->foreignId('id_skema')
                  ->constrained('skema', 'id_skema')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Isi Soal
            $table->text('soal_ia05');
            $table->string('opsi_jawaban_a');
            $table->string('opsi_jawaban_b');
            $table->string('opsi_jawaban_c');
            $table->string('opsi_jawaban_d');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_ia05');
    }
};