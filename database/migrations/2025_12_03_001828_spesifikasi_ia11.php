<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('spesifikasi_ia11', function (Blueprint $table) {
            $table->bigIncrements('id_spesifikasi_ia11');

            $table->string('deskripsi_spesifikasi', 500);

            // Hardening
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spesifikasi_ia11'); // <- PERBAIKAN
    }
};
