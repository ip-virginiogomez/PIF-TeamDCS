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
        Schema::create('docente_vacuna', function (Blueprint $table) {
            $table->id('idDocenteVacuna');
            $table->binary('documento')->nullable();
            $table->string('fechaSubida', 45)->nullable();
            $table->unsignedBigInteger('idEstadoVacuna')->nullable();
            $table->unsignedBigInteger('idTipoVacuna')->nullable();
            $table->string('runDocente', 10)->nullable();
            $table->timestamps();

            $table->foreign('idEstadoVacuna')
                ->references('idEstadoVacuna')
                ->on('estado_vacuna')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idTipoVacuna')
                ->references('idTipoVacuna')
                ->on('tipo_vacuna')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('runDocente')
                ->references('runDocente')
                ->on('docente')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente_vacuna');
    }
};
