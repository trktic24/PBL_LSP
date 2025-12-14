<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soal_ia06', function (Blueprint $table) {
            // Primary Key Custom
            $table->id('id_soal_ia06');

            $table->foreignId('id_skema')
                  ->constrained('skema', 'id_skema')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');

            // isi dari database soal_ia06
            $table->text('soal_ia06');
            $table->text('kunci_jawaban_ia06')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_ia06');
    }
};