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
            
            // ✅ Relasi ke portofolio
            $table->foreignId('id_portofolio')
                ->constrained('portofolio', 'id_portofolio')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            
            // ✅ Relasi ke ia08 (NULLABLE)
            $table->foreignId('id_ia08')
                ->nullable()
                ->constrained('ia08', 'id_ia08')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            // ✅ UBAH DARI BOOLEAN KE TEXT (seperti pencapaian_ia09)
            $table->text('is_valid')->nullable()->comment('Daftar dokumen yang valid');
            $table->text('is_asli')->nullable()->comment('Daftar dokumen yang asli');
            $table->text('is_terkini')->nullable()->comment('Daftar dokumen yang terkini');
            $table->text('is_memadai')->nullable()->comment('Daftar dokumen yang memadai');

            // ✅ Kolom untuk IA09 (Pertanyaan Wawancara)
            $table->text('daftar_pertanyaan_wawancara')->nullable();
            $table->text('kesimpulan_jawaban_asesi')->nullable();
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