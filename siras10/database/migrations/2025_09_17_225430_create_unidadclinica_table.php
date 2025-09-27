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
        Schema::create('unidadclinica', function (Blueprint $table) {
            $table->integer('idUnidadClinica')->primary();
            $table->integer('idCentroSalud')->nullable()->index('idcentrosalud_idx');
            $table->string('nombreUnidad', 45)->nullable();
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidadclinica');
    }
};
