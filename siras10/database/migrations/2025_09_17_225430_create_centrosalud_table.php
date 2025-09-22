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
        Schema::create('centrosalud', function (Blueprint $table) {
            $table->integer('idCentroSalud')->primary();
            $table->string('direccion', 100)->nullable();
            $table->integer('idCiudad')->nullable()->index('idciudad_idx');
            $table->integer('idTipoCentroSalud')->nullable()->index('idtipocentrosalud_idx');
            $table->string('nombreCentro', 45)->nullable();
            $table->integer('numContacto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centrosalud');
    }
};
