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
        Schema::create('alumno_carrera', function (Blueprint $table) {
            $table->id('idAlumnoCarrera');
            $table->string('runAlumno', 10)->nullable();
            $table->unsignedBigInteger('idSedeCarrera')->nullable();
            $table->timestamps();

            $table->foreign('runAlumno')
                ->references('runAlumno')
                ->on('alumno')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idSedeCarrera')
                ->references('idSedeCarrera')
                ->on('sede_carrera')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_carrera');
    }
};
