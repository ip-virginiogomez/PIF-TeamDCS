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
        Schema::create('cupo_distribucion', function (Blueprint $table) {
            $table->id('idCupoDistribucion');
            $table->unsignedBigInteger('idCupoOferta')->nullable();
            $table->unsignedBigInteger('idSedeCarrera')->nullable();
            $table->integer('cantCupos')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idCupoOferta')
                ->references('idCupoOferta')
                ->on('cupo_oferta')
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
        Schema::dropIfExists('cupo_distribucion');
    }
};
