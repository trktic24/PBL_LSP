<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal_ia05', function (Blueprint $table) {
            $table->id('id_soal_ia05'); // PK

            
            $table->foreignId('id_skema')
                  ->constrained('skema', 'id_skema')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // Isi Pertanyaan dan Opsi
            $table->text('pertanyaan_ia05');
            $table->string('opsi_a_ia05');
            $table->string('opsi_b_ia05');
            $table->string('opsi_c_ia05');
            $table->string('opsi_d_ia05');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_ia05');
    }
};