<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unit_kompetensis', function (Blueprint $table) {
            $table->id(); // Primary key (1, 2, 3, ...)
            
            $table->foreignId('id_skema')
                  ->constrained('skema', 'id_skema') // Menghubungkan ke 'id_skema' di tabel 'skema'
                  ->onDelete('cascade'); 

            $table->string('kode_unit'); 
            $table->string('judul_unit');
            $table->string('jenis_standard')->nullable(); 

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unit_kompetensis');
    }
};