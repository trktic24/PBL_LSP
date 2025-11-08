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
        Schema::create('respon_konfirmasi_dengan_orang_yang_relevan_mapa01', function (Blueprint $table) {
            // PK PERSIS kayak ERD
            $table->id('id_respon_konfirmasi_dengan_orang_relevan_mapa01');

            // --- FK 1 (ke asesi) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_data_sertifikasi_asesi');
            $table->foreign('id_data_sertifikasi_asesi', 'resp_konfirmasi_sertifikasi_fk')->references('id_data_sertifikasi_asesi')->on('data_sertifikasi_asesi')->onUpdate('cascade')->onDelete('cascade');

            // --- FK 2 (ke master-nya) ---
            // Kita bikin manual pake nama pendek biar gak error "too long"
            $table->foreignId('id_konfirmasi_dengan_orang_yang_relevan');
            $table->foreign('id_konfirmasi_dengan_orang_yang_relevan', 'resp_konfirmasi_master_fk')->references('id_konfirmasi_dengan_orang_yang_relevan')->on('Konfirmasi_dengan_orang_yang_relevan')->onUpdate('cascade')->onDelete('cascade');

            // Kolom sisa dari ERD
            $table->text('respon_asesi')->nullable();
            $table->string('tanda_tangan')->nullable()->comment('Path ke file gambar TTD');
            $table->date('tanggal')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_konfirmasi_dengan_orang_yang_relevan_mapa01');
    }
};
