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
        Schema::create('malla_sede_carrera', function (Blueprint $table) {
            $table->id('idMallaSedeCarrera');
            $table->date('fechaSubida')->nullable();
            $table->string('documento')->nullable();
            $table->string('nombre')->nullable();
            $table->unsignedBigInteger('idMallaCurricular')->nullable();
            $table->unsignedBigInteger('idSedeCarrera')->nullable();
            $table->timestamps();

            $table->foreign('idMallaCurricular')
                ->references('idMallaCurricular')
                ->on('malla_curricular')
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
        Schema::dropIfExists('malla_sede_carrera');
    }
};
