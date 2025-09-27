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
        Schema::create('centroformador', function (Blueprint $table) {
            $table->integer('idCentroFormador', true);
            $table->string('nombreCentroFormador', 45)->nullable();
            $table->integer('idTipoCentroFormador')->index('idtipocentroformador_idx');
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centroformador');
    }
};
