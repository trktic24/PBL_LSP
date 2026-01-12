<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pencapaian_spesifikasi_ia11', function (Blueprint $table) {
            $table->bigIncrements('id_pencapaian_spesifikasi_ia11');

            $table->unsignedBigInteger('id_ia11');
            $table->unsignedBigInteger('id_spesifikasi_ia11');

            // 0 = Tidak Memenuhi, 1 = Memenuhi
            $table->boolean('hasil_reviu')->default(false);
            $table->text('catatan_temuan')->nullable();

            $table->timestamps();

            // FK & CASCADE (ANTI DATA YATIM)
            $table->foreign('id_ia11')->references('id_ia11')->on('ia11')->onDelete('cascade');
            $table->foreign('id_spesifikasi_ia11')->references('id_spesifikasi_ia11')->on('spesifikasi_ia11')->onDelete('cascade');

            // Satu spesifikasi hanya boleh direviu satu kali per IA11
            $table->unique(['id_ia11', 'id_spesifikasi_ia11'], 'ia11_spesifikasi_unique');

            // INDEXES (anti lemot)
            $table->index('id_ia11');
            $table->index('id_spesifikasi_ia11');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian_spesifikasi_ia11');
    }
};
