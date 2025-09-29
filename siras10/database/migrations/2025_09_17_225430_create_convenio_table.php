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
        Schema::create('convenio', function (Blueprint $table) {
            $table->integer('idConvenio')->primary();
            $table->binary('documento')->nullable();
            $table->integer('idCentroFormador')->nullable()->index('idcentroformador_idx');
            $table->date('fechaSubida')->nullable();
            $table->integer('anioValidez')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenio');
    }
};
