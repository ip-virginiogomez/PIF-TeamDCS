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
        Schema::create('cupo_oferta', function (Blueprint $table) {
            $table->id('idCupoOferta');
            $table->unsignedBigInteger('idPeriodo')->nullable();
            $table->unsignedBigInteger('idUnidadClinica')->nullable();
            $table->unsignedBigInteger('idTipoPractica')->nullable();
            $table->unsignedBigInteger('idCarrera')->nullable();
            $table->integer('cantCupos')->nullable();
            $table->date('fechaEntrada')->nullable();
            $table->date('fechaSalida')->nullable();
            $table->time('horaEntrada')->nullable();
            $table->time('horaSalida')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idPeriodo')
                ->references('idPeriodo')
                ->on('periodo')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idUnidadClinica')
                ->references('idUnidadClinica')
                ->on('unidad_clinica')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idTipoPractica')
                ->references('idTipoPractica')
                ->on('tipo_practica')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idCarrera')
                ->references('idCarrera')
                ->on('carrera')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupo_oferta');
    }
};
