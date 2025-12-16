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
        Schema::create('cupo_oferta_horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('idCupoOferta');
            $table->string('diaSemana');
            $table->time('horaEntrada');
            $table->time('horaSalida');
            $table->timestamps();

            $table->foreign('idCupoOferta')->references('idCupoOferta')->on('cupo_oferta')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cupo_oferta_horarios');
    }
};
