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
        Schema::create('grupo', function (Blueprint $table) {
            $table->integer('idGrupo')->primary();
            $table->integer('idCupoDistribucion')->nullable()->index('idcupodistribucion_idx');
            $table->string('idDocenteCarrera', 15)->nullable()->index('iddocentecarrera_idx');
            $table->date('fechaCreacion')->nullable();
            $table->string('nombreGrupo', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
