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
        Schema::create('docente_carrera', function (Blueprint $table) {
            $table->id('idDocenteCarrera');
            $table->string('runDocente', 10)->nullable();
            $table->unsignedBigInteger('idSedeCarrera')->nullable();
            $table->timestamps();

            $table->foreign('runDocente')
                ->references('runDocente')
                ->on('docente')
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
        Schema::dropIfExists('docente_carrera');
    }
};
