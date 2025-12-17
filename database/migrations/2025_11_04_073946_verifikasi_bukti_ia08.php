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
        Schema::create('verifikasi_bukti_ia08', function (Blueprint $table) {
            $table->id('id_verifikasi_bukti_ia08');
            $table->foreignId('id_ia08')->constrained('ia08', 'id_ia08')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database verifikasi_bukti_ia08
            $table->text('materi_atau_substansi_wawancara')->comment('Materi atau substansi wawancara');
            $table->boolean('status_cekslist')->default(false)->comment('ceklist untuk hasil verifikasi IA08');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verifikasi_bukti_ia08');
    }
};
