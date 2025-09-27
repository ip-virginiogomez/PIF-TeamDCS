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
        Schema::table('asignatura', function (Blueprint $table) {
            $table->foreign(['idSedeCarrera'], 'fk_Asignatura_SedeCarrera')->references(['idSedeCarrera'])->on('sedecarrera')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idTipoPractica'], 'fk_Asignatura_TipoPractica')->references(['idTipoPractica'])->on('tipopractica')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asignatura', function (Blueprint $table) {
            $table->dropForeign('fk_Asignatura_SedeCarrera');
            $table->dropForeign('fk_Asignatura_TipoPractica');
        });
    }
};
