<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_penyesuaian_AK07', function (Blueprint $table) {
            $table->id('id_hasil_penyesuaian_AK07');

            // --- FOREIGN KEY ---
            // FK ke sesi sertifikasi (Induk) - Karena ini respons 1:1 per sesi asesmen
            $table->foreignId('id_data_sertifikasi_asesi')->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')->unique();

            // --- DATA INPUT ---
            $table->text('Acuan_Pembanding_Asesmen')->comment('Input teks untuk acuan pembanding');
            $table->text('Metode_Asesmen')->comment('Input teks untuk metode asesmen');
            $table->text('Instrumen_Asesmen')->comment('Input teks untuk instrumen asesmen');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_penyesuaian_AK07');
    }
};