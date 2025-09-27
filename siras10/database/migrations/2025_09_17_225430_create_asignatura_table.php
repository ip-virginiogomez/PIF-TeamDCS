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
        Schema::create('asignatura', function (Blueprint $table) {
            $table->integer('idAsignatura')->primary();
            $table->string('nombreAsignatura', 45)->nullable();
            $table->integer('idTipoPractica')->nullable()->index('idtipopractica_idx');
            $table->integer('idSedeCarrera')->nullable()->index('idsedecarrera_idx');
            $table->date('fechaCreacion')->nullable();
            $table->string('codAsignatura', 20)->nullable();
            $table->string('Semestre', 15)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignatura');
    }
};
