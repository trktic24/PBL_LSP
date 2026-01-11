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
        Schema::table('list_form', function (Blueprint $table) {
            $table->boolean('fr_ak_07')->default(false)->after('fr_ak_06');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_form', function (Blueprint $table) {
            $table->dropColumn('fr_ak_07');
        });
    }
};
