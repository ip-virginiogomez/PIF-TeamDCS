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
        Schema::table('centroformador', function (Blueprint $table) {
            $table->foreign(['idTipoCentroFormador'], 'fk_CentroFormador_TipoCentroFormador')->references(['idTipoCentroFormador'])->on('tipocentroformador')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centroformador', function (Blueprint $table) {
            $table->dropForeign('fk_CentroFormador_TipoCentroFormador');
        });
    }
};
