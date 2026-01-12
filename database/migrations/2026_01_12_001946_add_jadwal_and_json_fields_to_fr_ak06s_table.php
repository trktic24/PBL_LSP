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
        Schema::table('fr_ak06s', function (Blueprint $table) {
            $table->unsignedBigInteger('id_jadwal')->nullable()->after('id');
            $table->json('tinjauan')->nullable()->after('id_jadwal');
            $table->json('dimensi')->nullable()->after('tinjauan');
            $table->json('peninjau')->nullable()->after('dimensi');
            $table->text('komentar')->nullable()->after('peninjau');
            
            // If the table is meant to be linked to Jadwal, we could add a foreign key
            // but let's keep it simple for now to just fix the error.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fr_ak06s', function (Blueprint $table) {
            $table->dropColumn(['id_jadwal', 'tinjauan', 'dimensi', 'peninjau', 'komentar']);
        });
    }
};
