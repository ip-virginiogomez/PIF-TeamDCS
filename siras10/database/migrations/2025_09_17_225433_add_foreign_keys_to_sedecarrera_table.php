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
        Schema::table('sedecarrera', function (Blueprint $table) {
            $table->foreign(['idCarrera'], 'fk_SedeCarrera_Carrera')->references(['idCarrera'])->on('carrera')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idSede'], 'fk_SedeCarrera_Sede')->references(['idSede'])->on('sede')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sedecarrera', function (Blueprint $table) {
            $table->dropForeign('fk_SedeCarrera_Carrera');
            $table->dropForeign('fk_SedeCarrera_Sede');
        });
    }
};
