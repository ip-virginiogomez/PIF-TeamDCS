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
        Schema::create('coordinador_campo_clinico', function (Blueprint $table) {
            $table->id('idCoordinador');
            $table->unsignedBigInteger('idCentroFormador');
            $table->string('runUsuario', 10)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idCentroFormador')
                ->references('idCentroFormador')
                ->on('centro_formador')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('runUsuario')
                ->references('runUsuario')
                ->on('usuarios')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coordinador_campo_clinico');
    }
};
