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
        Schema::create('grupo', function (Blueprint $table) {
            $table->id('idGrupo');
            $table->unsignedBigInteger('idCupoDistribucion')->nullable();
            $table->unsignedBigInteger('idDocenteCarrera')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->string('nombreGrupo', 45)->nullable();
            $table->timestamps();

            $table->foreign('idCupoDistribucion')
                ->references('idCupoDistribucion')
                ->on('cupo_distribucion')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idDocenteCarrera')
                ->references('idDocenteCarrera')
                ->on('docente_carrera')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo');
    }
};
