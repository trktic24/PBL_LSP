<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spesifikasi_produk_ia11', function (Blueprint $table) {
            $table->id('id_spesifikasi_produk_ia11');
            $table->unsignedBigInteger('id_ia11')->unique();
            $table->string('dimensi_produk')->nullable();
            $table->string('berat_produk')->nullable();
            $table->timestamps();
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spesifikasi_produk_ia11');
    }
};