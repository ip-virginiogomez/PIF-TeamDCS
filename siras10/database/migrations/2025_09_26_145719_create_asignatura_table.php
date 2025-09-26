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
        Schema::create('asignatura', function (Blueprint $table) {
            $table->id('idAsignatura');
            $table->string('nombreAsignatura', 45)->nullable();
            $table->unsignedBigInteger('idTipoPractica')->nullable();
            $table->unsignedBigInteger('idSedeCarrera')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->string('codAsignatura', 20)->nullable();
            $table->string('Semestre', 15)->nullable();
            $table->timestamps();

            $table->foreign('idTipoPractica')
                ->references('idTipoPractica')
                ->on('tipo_practica')
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
        Schema::dropIfExists('asignatura');
    }
};
