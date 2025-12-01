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
        Schema::create('respon_hasil_ak03', function (Blueprint $table) {
            $table->id('id_respon_hasil_ak03');
            $table->foreignId('id_poin_ak03')->constrained('poin_ak03', 'id_poin_ak03')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // isi kolom tabel respon_hasil_ak03
            $table->text('komentar_lainnya')->nullable()->comment('isi sesuai komentar jika ada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_hasil_ak03');
    }
};
