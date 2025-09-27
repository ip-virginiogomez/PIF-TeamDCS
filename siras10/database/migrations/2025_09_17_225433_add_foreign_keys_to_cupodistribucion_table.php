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
        Schema::table('cupodistribucion', function (Blueprint $table) {
            $table->foreign(['idCupoOferta'], 'fk_CupoDistribucion_CupoOferta')->references(['idCupoOferta'])->on('cupooferta')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idSedeCarrera'], 'fk_CupoDistribucion_SedeCarrera')->references(['idSedeCarrera'])->on('sedecarrera')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupodistribucion', function (Blueprint $table) {
            $table->dropForeign('fk_CupoDistribucion_CupoOferta');
            $table->dropForeign('fk_CupoDistribucion_SedeCarrera');
        });
    }
};
