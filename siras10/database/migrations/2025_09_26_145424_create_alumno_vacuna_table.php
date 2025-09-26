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
        Schema::create('alumno_vacuna', function (Blueprint $table) {
            $table->id('idAlumnoVacuna');
            $table->binary('documento')->nullable();
            $table->date('fechaSubida')->nullable();
            $table->unsignedBigInteger('idEstadoVacuna')->nullable();
            $table->string('runAlumno', 10)->nullable();
            $table->unsignedBigInteger('idTipoVacuna')->nullable();
            $table->timestamps();

            $table->foreign('idEstadoVacuna')
                ->references('idEstadoVacuna')
                ->on('estado_vacuna')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('runAlumno')
                ->references('runAlumno')
                ->on('alumno')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idTipoVacuna')
                ->references('idTipoVacuna')
                ->on('tipo_vacuna')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacuna_alumno');
    }
};
