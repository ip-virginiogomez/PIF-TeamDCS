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
        Schema::create('tipo_centro_salud', function (Blueprint $table) {
            $table->id('idTipoCentroSalud');
            $table->string('nombreTipo', 45)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->string('acronimo', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_centro_salud');
    }
};
