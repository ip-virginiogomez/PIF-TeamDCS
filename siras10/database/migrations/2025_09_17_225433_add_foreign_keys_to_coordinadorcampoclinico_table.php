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
        Schema::table('coordinadorcampoclinico', function (Blueprint $table) {
            $table->foreign(['idCentroFormador'], 'fk_Coordinador_CentroFormador')->references(['idCentroFormador'])->on('centroformador')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['runUsuario'], 'fk_Coordinador_Usuario')->references(['runUsuario'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coordinadorcampoclinico', function (Blueprint $table) {
            $table->dropForeign('fk_Coordinador_CentroFormador');
            $table->dropForeign('fk_Coordinador_Usuario');
        });
    }
};
