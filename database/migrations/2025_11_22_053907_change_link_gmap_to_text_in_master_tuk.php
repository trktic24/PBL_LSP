<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('master_tuk', function (Blueprint $table) {
            // Kita ubah kolom 'link_gmap' jadi tipe TEXT
            // function change() butuh library 'doctrine/dbal' (biasanya udah ada di Laravel baru)
            $table->text('link_gmap')->change();
        });
    }

    public function down(): void
    {
        Schema::table('master_tuk', function (Blueprint $table) {
            // Kembalikan ke string kalau di-rollback
            $table->string('link_gmap', 255)->change();
        });
    }
};