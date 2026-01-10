<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pencapaian_performa_ia11', function (Blueprint $table) {

            $table->bigIncrements('id_pencapaian_performa_ia11');

            $table->unsignedBigInteger('id_ia11');
            $table->unsignedBigInteger('id_performa_ia11');

            // Harus diisi asesor (tidak boleh null)
            $table->boolean('hasil_reviu');

            $table->text('catatan_temuan')->nullable();

            $table->timestamps();

            // Header asesmen → boleh cascade (hapus asesmen = hapus detail)
            $table->foreign('id_ia11')
                  ->references('id_ia11')->on('ia11')
                  ->onDelete('cascade');

            // MASTER TIDAK BOLEH DIHAPUS kalau sudah dipakai
            $table->foreign('id_performa_ia11')
                  ->references('id_performa_ia11')->on('performa_ia11')
                  ->onDelete('restrict');

            // Tidak boleh ada duplikasi hasil reviu
            $table->unique(['id_ia11', 'id_performa_ia11'], 'ia11_performa_unique');

            // Index PDF join
            $table->index('id_ia11');
            $table->index('id_performa_ia11');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian_performa_ia11');
    }
};
