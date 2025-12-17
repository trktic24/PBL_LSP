<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('respon_potensi_AK07', function (Blueprint $table) {
            $table->id('id_respon_potensi_AK07');

            // --- FOREIGN KEYS ---
            // FK ke sesi sertifikasi (Induk)
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi');
            // FK ke poin statis yang dipilih
            $table->foreignId('id_poin_potensi_AK07')->constrained('poin_potensi_AK07', 'id_poin_potensi_AK07');

            // --- DATA RESPON ---
            $table->text('respon_asesor')->nullable()->comment('Catatan asesor terkait poin potensi ini');

            $table->timestamps();
            
            // Mencegah duplikasi entri (opsional, tergantung logic app)
            $table->unique(['id_data_sertifikasi_asesi', 'id_poin_potensi_AK07'], 'unique_potensi_respon');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('respon_potensi_AK07');
    }
};