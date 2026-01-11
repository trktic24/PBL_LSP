<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check first if table exists to prevent 'Table already exists' if manually run
        if (!Schema::hasTable('master_form_templates')) {
            Schema::create('master_form_templates', function (Blueprint $col) {
                $col->id();
                $col->bigInteger('id_skema')->unsigned();
                $col->string('form_code', 20); // e.g. 'FR.IA.02', 'FR.MAPA.01'
                $col->json('content'); // Stores specific template fields as JSON
                $col->timestamps();

                $col->foreign('id_skema')->references('id_skema')->on('skema')->onDelete('cascade');
                
                // Original unique constraint (will be modified by subsequent migration)
                $col->unique(['id_skema', 'form_code']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_form_templates');
    }
};
