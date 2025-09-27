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
        Schema::table('vacunaalumno', function (Blueprint $table) {
            $table->foreign(['runAlumno'], 'fk_VacunaAlumno_Alumno')->references(['runAlumno'])->on('alumno')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idEstadoVacuna'], 'fk_VacunaAlumno_EstadoVacuna')->references(['idEstadoVacuna'])->on('estadovacuna')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idTipoVacuna'], 'fk_VacunaAlumno_TipoVacuna')->references(['idTipoVacuna'])->on('tipovacuna')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vacunaalumno', function (Blueprint $table) {
            $table->dropForeign('fk_VacunaAlumno_Alumno');
            $table->dropForeign('fk_VacunaAlumno_EstadoVacuna');
            $table->dropForeign('fk_VacunaAlumno_TipoVacuna');
        });
    }
};
