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
        Schema::create('bukti_portofolio_ia08_ia09', function (Blueprint $table) {
            $table->id('id_bukti_portofolio');
            $table->foreignId('id_portofolio')->constrained('portofolio', 'id_portofolio')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_ia08')->constrained('ia08', 'id_ia08')->onUpdate('cascade')->onDelete('cascade');

            // isi dari database bukti_portofolio_ia08_ia09
            $table->boolean('is_valid')->default(null)->comment('ya/tidak');      // ya/tidak
            $table->boolean('is_asli')->default(null)->comment('ya/tidak');       // ya/tidak
            $table->boolean('is_terkini')->default(null)->comment('ya/tidak');    // ya/tidak
            $table->boolean('is_memadai')->default(null)->comment('ya/tidak');    // ya/tidak
            $table->text('kesimpulan_jawaban_asesi')->nullable();
            $table->boolean('pencapaian_ia09')->default(null)->comment('Apakah asesi mencapai IA09 berdasarkan bukti portofolio yang diajukan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukti_portofolio_ia08_ia09');
    }
};
