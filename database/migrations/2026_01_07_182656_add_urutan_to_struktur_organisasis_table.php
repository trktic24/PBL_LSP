<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // CEK DULU: Hanya tambahkan jika kolom 'urutan' BELUM ADA
        if (!Schema::hasColumn('struktur_organisasis', 'urutan')) {
            Schema::table('struktur_organisasis', function (Blueprint $table) {
                $table->integer('urutan')->default(0)->after('jabatan'); 
            });
        }
    }

    public function down(): void
    {
        // Cek dulu sebelum menghapus
        if (Schema::hasColumn('struktur_organisasis', 'urutan')) {
            Schema::table('struktur_organisasis', function (Blueprint $table) {
                $table->dropColumn('urutan');
            });
        }
    }
};