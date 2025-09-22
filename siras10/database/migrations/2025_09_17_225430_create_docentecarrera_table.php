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
        Schema::create('docentecarrera', function (Blueprint $table) {
            $table->string('idDocenteCarrera', 15)->primary();
            $table->string('runDocente', 10)->nullable()->index('rundocente_idx');
            $table->integer('idSedeCarrera')->nullable()->index('idsedecarrera_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docentecarrera');
    }
};
