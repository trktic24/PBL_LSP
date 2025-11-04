<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kunci_IA06', function (Blueprint $table) {
            $table->id('id_kunci_IA06');
            $table->unsignedBigInteger('id_soal_IA06');
            $table->string('kunci_IA06');
            $table->timestamps();

            $table->foreign('id_soal_IA06')
                  ->references('id_soal_IA06')
                  ->on('soal_IA06')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kunci_IA06');
    }
};

