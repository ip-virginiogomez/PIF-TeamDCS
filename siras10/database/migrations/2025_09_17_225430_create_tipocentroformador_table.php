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
        Schema::create('tipocentroformador', function (Blueprint $table) {
            $table->integer('idTipoCentroFormador', true);
            $table->string('nombreTipo', 45);
            $table->date('fechaCreacion')->nullable();
            $table->string('acronimo', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipocentroformador');
    }
};
