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
        Schema::table('docentevacuna', function (Blueprint $table) {
            $table->foreign(['runDocente'], 'fk_DocenteVacuna_Docente')->references(['runDocente'])->on('docente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idEstadoVacuna'], 'fk_DocenteVacuna_EstadoVacuna')->references(['idEstadoVacuna'])->on('estadovacuna')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idTipoVacuna'], 'fk_DocenteVacuna_TipoVacuna')->references(['idTipoVacuna'])->on('tipovacuna')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docentevacuna', function (Blueprint $table) {
            $table->dropForeign('fk_DocenteVacuna_Docente');
            $table->dropForeign('fk_DocenteVacuna_EstadoVacuna');
            $table->dropForeign('fk_DocenteVacuna_TipoVacuna');
        });
    }
};
