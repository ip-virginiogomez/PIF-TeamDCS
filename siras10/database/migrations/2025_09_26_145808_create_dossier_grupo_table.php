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
        Schema::create('dossier_grupo', function (Blueprint $table) {
            $table->id('idDossierGrupo');
            $table->string('runAlumno', 10)->nullable();
            $table->unsignedBigInteger('idGrupo')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('runAlumno')
                ->references('runAlumno')
                ->on('alumno')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idGrupo')
                ->references('idGrupo')
                ->on('grupo')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossier_grupo');
    }
};
