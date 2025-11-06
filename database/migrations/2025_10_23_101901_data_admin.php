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
        Schema::create('admin', function (Blueprint $table) {
            
            $table->id('id_admin');
            $table->foreignId('id_user')->constrained('users', 'id_user')->onUpdate('cascade')->onDelete('restrict');

            // isi dari database admin
            $table->string('nama_admin');
            $table->string('tanda_tangan_admin')->nullable()->comment('Path ke file tanda tangan admin');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin');
    }
};
