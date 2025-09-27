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
        Schema::table('alumnocarrera', function (Blueprint $table) {
            $table->foreign(['runAlumno'], 'fk_AlumnoCarrera_Alumno')->references(['runAlumno'])->on('alumno')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idSedeCarrera'], 'fk_AlumnoCarrera_SedeCarrera')->references(['idSedeCarrera'])->on('sedecarrera')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alumnocarrera', function (Blueprint $table) {
            $table->dropForeign('fk_AlumnoCarrera_Alumno');
            $table->dropForeign('fk_AlumnoCarrera_SedeCarrera');
        });
    }
};
