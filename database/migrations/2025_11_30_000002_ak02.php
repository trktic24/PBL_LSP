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
        Schema::create('ak02', function (Blueprint $table) {
            $table->id('id_ak02');
            $table->foreignId('id_poin_ak02')->constrained('poin_ak02', 'id_poin_ak02')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom tabel respon_ak02
            $table->enum('kompeten', ['Kompeten', 'Belum Kompeten'])->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('komentar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ak02');
    }
};
