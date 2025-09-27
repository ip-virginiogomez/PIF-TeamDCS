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
        Schema::create('dossiergrupo', function (Blueprint $table) {
            $table->integer('idDossierGrupo')->primary();
            $table->string('runAlumno', 10)->nullable()->index('runalumno_idx');
            $table->integer('idGrupo')->nullable()->index('idgrupo_idx');
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiergrupo');
    }
};
