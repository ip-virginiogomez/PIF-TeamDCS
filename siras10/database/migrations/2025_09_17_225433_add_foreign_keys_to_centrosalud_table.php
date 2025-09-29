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
        Schema::table('centrosalud', function (Blueprint $table) {
            $table->foreign(['idCiudad'], 'fk_CentroSalud_Ciudad')->references(['idCiudad'])->on('ciudad')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idTipoCentroSalud'], 'fk_CentroSalud_TipoCentroSalud')->references(['idTipoCentroSalud'])->on('tipocentrosalud')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('centrosalud', function (Blueprint $table) {
            $table->dropForeign('fk_CentroSalud_Ciudad');
            $table->dropForeign('fk_CentroSalud_TipoCentroSalud');
        });
    }
};
