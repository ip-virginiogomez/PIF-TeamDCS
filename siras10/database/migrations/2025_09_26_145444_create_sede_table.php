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
        Schema::create('sede', function (Blueprint $table) {
            $table->id('idSede');
            $table->string('nombreSede', 45)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->unsignedBigInteger('idCentroFormador');
            $table->date('fechaCreacion')->nullable();
            $table->string('numContacto', 12)->nullable();
            $table->timestamps();

            $table->foreign('idCentroFormador')
                ->references('idCentroFormador')
                ->on('centro_formador')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sede');
    }
};
