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
        Schema::create('cupo_demanda', function (Blueprint $table) {
            $table->id('idDemandaCupo');
            $table->unsignedBigInteger('idPeriodo');
            $table->unsignedBigInteger('idSedeCarrera');
            $table->integer('cuposSolicitados');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('idPeriodo')->references('idPeriodo')->on('periodo');
            $table->foreign('idSedeCarrera')->references('idSedeCarrera')->on('sede_carrera');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupo_demanda');
    }
};
