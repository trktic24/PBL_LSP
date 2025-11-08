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
        Schema::create('soal_ia06', function (Blueprint $table) {
            $table->id('id_soal_ia06');

            // isi dari database soal_ia06
            $table->text('soal_ia06');
            $table->text('kunci_jawaban_ia06')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soal_ia06');
    }
};
