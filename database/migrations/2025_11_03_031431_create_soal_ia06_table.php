<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('soal_IA06', function (Blueprint $table) {
            $table->id('id_soal_IA06');
            $table->string('soal_IA06');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_IA06');
    }
};
