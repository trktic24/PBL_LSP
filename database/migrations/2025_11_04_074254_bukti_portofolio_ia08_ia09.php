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

        // Menggunakan text agar bisa menampung banyak nama dokumen yang dipisahkan koma
        $table->text('is_valid')->comment('Daftar dokumen yang valid'); 
        $table->text('is_asli')->comment('Daftar dokumen yang asli'); 
        $table->text('is_terkini')->comment('Daftar dokumen yang terkini'); 
        $table->text('is_memadai')->comment('Daftar dokumen yang memadai'); 

        $table->text('daftar_pertanyaan_wawancara')->nullable();
        $table->text('kesimpulan_jawaban_asesi')->nullable();
        $table->boolean('pencapaian_ia09')->nullable()->comment('Apakah asesi mencapai IA09 berdasarkan bukti portofolio yang diajukan');
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
