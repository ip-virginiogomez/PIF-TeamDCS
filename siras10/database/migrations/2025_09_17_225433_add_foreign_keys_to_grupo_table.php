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
        Schema::table('grupo', function (Blueprint $table) {
            $table->foreign(['idCupoDistribucion'], 'fk_Grupo_CupoDistribucion')->references(['idCupoDistribucion'])->on('cupodistribucion')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idDocenteCarrera'], 'fk_Grupo_DocenteCarrera')->references(['idDocenteCarrera'])->on('docentecarrera')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grupo', function (Blueprint $table) {
            $table->dropForeign('fk_Grupo_CupoDistribucion');
            $table->dropForeign('fk_Grupo_DocenteCarrera');
        });
    }
};
