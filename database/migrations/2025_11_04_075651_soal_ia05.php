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
        Schema::create('soal_ia05', function (Blueprint $table) {
            $table->id('id_soal_ia05');

            // isi dari database soal_ia06
<<<<<<< HEAD
            $table->string('soal_ia05');
=======
            $table->text('soal_ia05');
            $table->string('opsi_jawaban_a');
            $table->string('opsi_jawaban_b');
            $table->string('opsi_jawaban_c');
            $table->string('opsi_jawaban_d');

>>>>>>> 0cc37f75099885ce4dcba4e5853fccaa3b2be4af
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ia05');
    }
};
