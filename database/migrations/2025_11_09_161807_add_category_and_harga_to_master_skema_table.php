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
        Schema::table('skema', function (Blueprint $table) {
            // Tambahkan kolom category (misalnya string)
            $table->string('category')->nullable()->after('deskripsi_skema'); 
            
            // Tambahkan kolom harga (misalnya integer/bigInteger)
            $table->bigInteger('harga')->nullable()->after('category'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skema', function (Blueprint $table) {
            // Hapus kolom yang ditambahkan jika migrasi di-rollback
            $table->dropColumn(['category', 'harga']);
        });
    }
};
