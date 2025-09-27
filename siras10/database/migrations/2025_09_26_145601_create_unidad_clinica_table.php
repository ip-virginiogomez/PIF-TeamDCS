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
        Schema::create('unidad_clinica', function (Blueprint $table) {
            $table->id('idUnidadClinica');
            $table->unsignedBigInteger('idCentroSalud')->nullable();
            $table->string('nombreUnidad', 45)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idCentroSalud')
                ->references('idCentroSalud')
                ->on('centro_salud')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidad_clinica');
    }
};
