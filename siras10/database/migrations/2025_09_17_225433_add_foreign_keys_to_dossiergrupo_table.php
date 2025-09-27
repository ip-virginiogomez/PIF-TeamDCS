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
        Schema::table('dossiergrupo', function (Blueprint $table) {
            $table->foreign(['runAlumno'], 'fk_DossierGrupo_Alumno')->references(['runAlumno'])->on('alumno')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idGrupo'], 'fk_DossierGrupo_Grupo')->references(['idGrupo'])->on('grupo')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dossiergrupo', function (Blueprint $table) {
            $table->dropForeign('fk_DossierGrupo_Alumno');
            $table->dropForeign('fk_DossierGrupo_Grupo');
        });
    }
};
