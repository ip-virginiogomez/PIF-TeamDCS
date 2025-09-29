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
        Schema::create('tipo_vacuna', function (Blueprint $table) {
            $table->id('idTipoVacuna');
            $table->string('nombreVacuna', 45)->nullable();
            $table->integer('duracion')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_vacuna');
    }
};
