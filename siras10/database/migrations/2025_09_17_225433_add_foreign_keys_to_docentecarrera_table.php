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
        Schema::table('docentecarrera', function (Blueprint $table) {
            $table->foreign(['runDocente'], 'fk_DocenteCarrera_Docente')->references(['runDocente'])->on('docente')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idSedeCarrera'], 'fk_DocenteCarrera_SedeCarrera')->references(['idSedeCarrera'])->on('sedecarrera')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('docentecarrera', function (Blueprint $table) {
            $table->dropForeign('fk_DocenteCarrera_Docente');
            $table->dropForeign('fk_DocenteCarrera_SedeCarrera');
        });
    }
};
