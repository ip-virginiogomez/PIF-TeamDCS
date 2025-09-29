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
        Schema::create('alumnocarrera', function (Blueprint $table) {
            $table->integer('idAlumnoCarrera')->primary();
            $table->string('runAlumno', 10)->nullable()->index('runalumno_idx');
            $table->integer('idSedeCarrera')->nullable()->index('idsedecarrera_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnocarrera');
    }
};
