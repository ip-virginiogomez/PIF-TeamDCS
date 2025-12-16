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
            $table->string('runUsuario', 10);
            $table->date('fechaCreacion')->nullable();

            $table->foreign('idCentroFormador')
                ->references('idCentroFormador')
                ->on('centro_formador')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('runUsuario')
                ->references('runUsuario')
                ->on('usuarios')
                ->onUpdate('cascade')
                ->onDelete('cascade');
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
