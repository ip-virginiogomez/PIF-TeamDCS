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
        Schema::create('sede_carrera', function (Blueprint $table) {
            $table->id('idSedeCarrera');
            $table->string('nombreSedeCarrera', 45)->nullable();
            $table->unsignedBigInteger('idSede');
            $table->unsignedBigInteger('idCarrera');
            $table->string('codigoCarrera', 20)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idSede')
                ->references('idSede')
                ->on('sede')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idCarrera')
                ->references('idCarrera')
                ->on('carrera')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sede_carrera');
    }
};
