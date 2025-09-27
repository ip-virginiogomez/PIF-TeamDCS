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
        Schema::table('programa', function (Blueprint $table) {
            $table->foreign(['idAsignatura'], 'fk_Programa_Asignatura')->references(['idAsignatura'])->on('asignatura')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programa', function (Blueprint $table) {
            $table->dropForeign('fk_Programa_Asignatura');
        });
    }
};
