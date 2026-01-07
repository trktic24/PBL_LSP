<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('struktur_organisasis', function (Blueprint $table) {
        // Menambahkan kolom urutan, default 0
        $table->integer('urutan')->default(0)->after('jabatan'); 
    });
}

public function down(): void
{
    Schema::table('struktur_organisasis', function (Blueprint $table) {
        $table->dropColumn('urutan');
    });
}
};
