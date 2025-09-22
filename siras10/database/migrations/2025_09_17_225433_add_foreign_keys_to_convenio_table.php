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
        Schema::table('convenio', function (Blueprint $table) {
            $table->foreign(['idCentroFormador'], 'fk_Convenio_CentroFormador')->references(['idCentroFormador'])->on('centroformador')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('convenio', function (Blueprint $table) {
            $table->dropForeign('fk_Convenio_CentroFormador');
        });
    }
};
