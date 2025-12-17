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
        Schema::create('respon_ak02', function (Blueprint $table) {
            $table->id('id_respon_ak02');
            $table->foreignId('id_poin_ak02')->constrained('poin_ak02', 'id_poin_ak02')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');
            $table->string('respon')->nullable(); // Assuming respon might be text input
            $table->enum('kompeten', ['K', 'BK'])->nullable(); // K = Kompeten, BK = Belum Kompeten
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_ak02');
    }
};
