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
        Schema::create('vacunaalumno', function (Blueprint $table) {
            $table->integer('idVacunaAlumno')->primary();
            $table->binary('documento')->nullable();
            $table->date('fechaSubida')->nullable();
            $table->integer('idEstadoVacuna')->nullable()->index('idestadovacuna_idx');
            $table->string('runAlumno', 10)->nullable()->index('runalumno_idx');
            $table->integer('idTipoVacuna')->nullable()->index('idtipovacuna_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacunaalumno');
    }
};
