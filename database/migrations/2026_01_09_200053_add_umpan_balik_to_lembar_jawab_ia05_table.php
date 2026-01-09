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
        Schema::table('lembar_jawab_ia05', function (Blueprint $table) {
            $table->text('umpan_balik')->nullable()->after('pencapaian_ia05');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lembar_jawab_ia05', function (Blueprint $table) {
             $table->dropColumn('umpan_balik');
            //
        });
    }
};
