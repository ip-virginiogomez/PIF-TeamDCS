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
        Schema::create('personal', function (Blueprint $table) {
            $table->id('idPersonal');
            $table->unsignedBigInteger('idCentroSalud')->nullable();
            $table->string('runUsuario', 10)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();

            $table->foreign('idCentroSalud')
                ->references('idCentroSalud')
                ->on('centro_salud')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('runUsuario')
                ->references('runUsuario')
                ->on('usuarios')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
