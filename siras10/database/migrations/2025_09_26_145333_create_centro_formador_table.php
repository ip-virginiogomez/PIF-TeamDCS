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
        Schema::create('centro_formador', function (Blueprint $table) {
            $table->id('idCentroFormador');
            $table->string('nombreCentroFormador', 45)->nullable();
            $table->unsignedBigInteger('idTipoCentroFormador');
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idTipoCentroFormador')
                ->references('idTipoCentroFormador')
                ->on('tipo_centro_formador')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centro_formador');
    }
};
