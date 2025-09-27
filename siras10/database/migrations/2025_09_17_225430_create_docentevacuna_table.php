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
        Schema::create('docentevacuna', function (Blueprint $table) {
            $table->integer('idDocenteVacuna')->primary();
            $table->binary('documento')->nullable();
            $table->string('fechaSubida', 45)->nullable();
            $table->integer('idEstadoVacuna')->nullable()->index('idestadovacuna_idx');
            $table->integer('idTipoVacuna')->nullable()->index('idtipovacuna_idx');
            $table->string('runDocente', 10)->nullable()->index('rundocente_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentevacuna');
    }
};
