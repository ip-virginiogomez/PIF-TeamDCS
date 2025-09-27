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
        Schema::create('sedecarrera', function (Blueprint $table) {
            $table->integer('idSedeCarrera')->primary();
            $table->string('nombreSedeCarrera', 45)->nullable();
            $table->integer('idSede')->index('idsede_idx');
            $table->integer('idCarrera')->index('idcarrera_idx');
            $table->string('codigoCarrera', 20)->nullable();
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sedecarrera');
    }
};
