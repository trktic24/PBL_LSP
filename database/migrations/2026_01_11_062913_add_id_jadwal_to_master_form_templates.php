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
        // 1. Tambah kolom id_jadwal
        Schema::table('master_form_templates', function (Blueprint $table) {
            $table->foreignId('id_jadwal')->nullable()->after('id_skema')
                  ->constrained('jadwal', 'id_jadwal')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });

        // 2. Tambah yang Baru DULU, baru hapus yang lama
        Schema::table('master_form_templates', function (Blueprint $table) {
            $table->unique(['id_skema', 'id_jadwal', 'form_code'], 'idx_template_unique');
            $table->dropUnique('master_form_templates_id_skema_form_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_form_templates', function (Blueprint $table) {
            $table->unique(['id_skema', 'form_code'], 'master_form_templates_id_skema_form_code_unique');
            $table->dropUnique('idx_template_unique');
            $table->dropForeign(['id_jadwal']);
            $table->dropColumn('id_jadwal');
        });
    }
};
