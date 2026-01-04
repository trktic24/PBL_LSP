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
            
            // ✅ Relasi ke data_portofolio (sesuai nama tabel yang benar)
            $table->foreignId('id_portofolio')
                ->constrained('portofolio', 'id_portofolio')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            // ✅ Relasi ke ia08 (NULLABLE - tidak wajib ada)
            $table->foreignId('id_ia08')
                ->nullable() // ✅ Bisa null, tidak wajib IA08 terisi
                ->constrained('ia08', 'id_ia08')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // ✅ Kolom validasi untuk IA08 (NULLABLE - default untuk IA09)
            $table->boolean('is_valid')->nullable()->comment('Apakah dokumen valid (untuk IA08)');
            $table->boolean('is_asli')->nullable()->comment('Apakah dokumen asli (untuk IA08)');
            $table->boolean('is_terkini')->nullable()->comment('Apakah dokumen terkini (untuk IA08)');
            $table->boolean('is_memadai')->nullable()->comment('Apakah dokumen memadai (untuk IA08)');

            // ✅ Kolom untuk IA09 (Pertanyaan Wawancara) - sesuai nama di database
            $table->text('daftar_pertanyaan_wawancara')->nullable()->comment('Pertanyaan wawancara dari asesor');
            $table->text('kesimpulan_jawaban_asesi')->nullable()->comment('Kesimpulan jawaban asesi dari asesor');
            
            // ✅ Pencapaian sebagai STRING ('Ya' atau 'Tidak')
            $table->string('pencapaian_ia09', 10)->nullable()->comment('Pencapaian IA09: Ya atau Tidak');
            
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