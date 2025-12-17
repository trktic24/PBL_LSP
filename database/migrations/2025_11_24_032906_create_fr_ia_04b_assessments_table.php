<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('fr_ia_04b_assessments', function (Blueprint $table) {
        $table->id();
        $table->string('judul_kegiatan')->nullable();
        $table->string('nama_asesi')->default('Tatang Sidartang'); // Default sesuai form
        $table->string('nama_asesor')->nullable();
        $table->string('no_reg_asesor')->nullable();
        $table->date('tanggal')->nullable();
        
        // Aspek Penilaian Baris 1
        $table->text('lingkup_penyajian_1')->nullable();
        $table->text('pertanyaan_1')->nullable();
        $table->text('tanggapan_1')->nullable();
        $table->text('kuk_elemen_1')->nullable();
        $table->boolean('is_kompeten_1')->default(false); // Checkbox Ya/Tidak

        // Aspek Penilaian Baris 2
        $table->text('lingkup_penyajian_2')->nullable();
        $table->text('pertanyaan_2')->nullable();
        $table->text('tanggapan_2')->nullable();
        $table->text('kuk_elemen_2')->nullable();
        $table->boolean('is_kompeten_2')->default(false);

        // Rekomendasi
        $table->enum('rekomendasi', ['kompeten', 'belum_kompeten'])->nullable();
        
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fr_ia_04b_assessments');
    }
};
