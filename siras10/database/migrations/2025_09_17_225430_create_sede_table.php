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
        Schema::create('sede', function (Blueprint $table) {
            $table->integer('idSede')->primary();
            $table->string('nombreSede', 45)->nullable();
            $table->string('direccion', 100)->nullable();
            $table->integer('idCentroFormador')->index('idcentroformador_idx');
            $table->date('fechaCreacion')->nullable();
            $table->integer('numContacto')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sede');
    }
};
