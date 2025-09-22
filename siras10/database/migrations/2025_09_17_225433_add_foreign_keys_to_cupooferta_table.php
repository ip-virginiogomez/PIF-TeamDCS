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
        Schema::table('cupooferta', function (Blueprint $table) {
            $table->foreign(['idCarrera'], 'fk_CupoOferta_Carrera')->references(['idCarrera'])->on('carrera')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idPeriodo'], 'fk_CupoOferta_Periodo')->references(['idPeriodo'])->on('periodo')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idTipoPractica'], 'fk_CupoOferta_TipoPractica')->references(['idTipoPractica'])->on('tipopractica')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idUnidadClinica'], 'fk_CupoOferta_UnidadClinica')->references(['idUnidadClinica'])->on('unidadclinica')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupooferta', function (Blueprint $table) {
            $table->dropForeign('fk_CupoOferta_Carrera');
            $table->dropForeign('fk_CupoOferta_Periodo');
            $table->dropForeign('fk_CupoOferta_TipoPractica');
            $table->dropForeign('fk_CupoOferta_UnidadClinica');
        });
    }
};
