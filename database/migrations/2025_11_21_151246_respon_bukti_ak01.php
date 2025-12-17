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
        Schema::create('respon_bukti_ak01', function (Blueprint $table) {
            $table->id('id_respon_bukti_ak01');
            
            // Relasi ke Data Sertifikasi
            $table->foreignId('id_data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            // Relasi ke Master Bukti
            $table->foreignId('id_bukti_ak01')
                  ->constrained('bukti_ak01', 'id_bukti_ak01')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            $table->string('respon')->nullable()->comment('deskripsi lainnya jika bukti = Lainnya');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respon_bukti_ak01');
    }
};
