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
        Schema::create('centro_salud', function (Blueprint $table) {
            $table->id('idCentroSalud');
            $table->string('direccion', 100)->nullable();
            $table->unsignedBigInteger('idCiudad')->nullable();
            $table->unsignedBigInteger('idTipoCentroSalud')->nullable();
            $table->string('nombreCentro', 45)->nullable();
            $table->integer('numContacto')->nullable();
            $table->timestamps();

            $table->foreign('idCiudad')
                ->references('idCiudad')
                ->on('ciudad')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idTipoCentroSalud')
                ->references('idTipoCentroSalud')
                ->on('tipo_centro_salud')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centro_salud');
    }
};
