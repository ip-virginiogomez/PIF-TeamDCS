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
        Schema::create('cupooferta', function (Blueprint $table) {
            $table->integer('idCupoOferta')->primary();
            $table->integer('idPeriodo')->nullable()->index('idperiodo_idx');
            $table->integer('idUnidadClinica')->nullable()->index('idunidadclinica_idx');
            $table->integer('idTipoPractica')->nullable()->index('idtipopractica_idx');
            $table->integer('idCarrera')->nullable()->index('idcarrera_idx');
            $table->integer('cantCupos')->nullable();
            $table->date('fechaEntrada')->nullable();
            $table->date('fechaSalida')->nullable();
            $table->time('horaEntrada')->nullable();
            $table->time('horaSalida')->nullable();
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupooferta');
    }
};
