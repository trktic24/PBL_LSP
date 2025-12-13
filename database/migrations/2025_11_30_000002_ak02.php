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

            // 1. RELASI: Milik Asesi Siapa?
            // Pastikan nama tabel referensinya 'data_sertifikasi_asesi' sesuai file migration 2025_10_30_124822
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // 2. RELASI: Menilai Unit Kompetensi Apa?
            // Pastikan nama tabel referensinya 'unit_kompetensi' sesuai file migration 2025_10_23_041918
            $table->foreignId('id_unit_kompetensi')
                  ->constrained('unit_kompetensi', 'id_unit_kompetensi')
                  ->onUpdate('cascade')->onDelete('cascade');

            // 3. DATA PENILAIAN
            // Menyimpan checklist bukti (TL, L, PT, dll) dalam bentuk JSON array
            $table->json('jenis_bukti')->nullable();

            // Keputusan per unit (Kompeten / Belum Kompeten)
            $table->enum('kompeten', ['Kompeten', 'Belum Kompeten'])->nullable();

            // Catatan tambahan
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