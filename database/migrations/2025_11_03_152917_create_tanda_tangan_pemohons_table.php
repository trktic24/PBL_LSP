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
        Schema::create('tanda_tangan_pemohons', function (Blueprint $table) {
            $table->id(); 
            
            // 1. KUNCI ASING (Foreign Key)
            // Menghubungkan tabel ini ke data identitas asesi
            // Asumsi: Primary Key di tabel 'asesi' adalah 'id_asesi'
            $table->foreignId('id_asesi')
                  ->constrained('asesi', 'id_asesi') 
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
            
            // 2. KOLOM DATA TANDA TANGAN
            // Menggunakan longText untuk menyimpan string Base64 yang panjang
            $table->longText('data_tanda_tangan'); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanda_tangan_pemohons');
    }
};