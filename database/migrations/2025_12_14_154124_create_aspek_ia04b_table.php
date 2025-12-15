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
        Schema::create('aspek_ia04B', function (Blueprint $table) {
            // PK
            $table->id('id_aspek_ia04B'); 

            // 👇 KOREKSI FK: Menggunakan foreignId() dan secara eksplisit menamai kolom yang dirujuk
            $table->foreignId('id_data_sertifikasi_asesi')
                  // Parameter kedua: Nama kolom PK di tabel target ('data_sertifikasi_asesi')
                  ->constrained('data_sertifikasi_asesi', 'id_data_sertifikasi_asesi') 
                  ->onDelete('cascade');

            // Field Data Respons (sisanya tetap sama)
            $table->text('respon_lingkup_penyajian_proyek')->nullable();
            $table->text('respon_daftar_pertanyaan')->nullable();
            $table->text('respon_daftar_tanggapan')->nullable();
            $table->text('respon_kesesuaian_standar_kompetensi')->nullable();
            $table->enum('respon_pencapaian', ['Ya', 'Tidak', 'Y', 'T'])->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspek_ia04B');
    }
};