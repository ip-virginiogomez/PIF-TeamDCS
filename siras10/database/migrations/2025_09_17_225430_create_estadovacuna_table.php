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
        Schema::create('estadovacuna', function (Blueprint $table) {
            $table->integer('idEstadoVacuna')->primary();
            $table->string('nombreEstado', 45)->nullable();
            $table->string('descripcion', 150)->nullable();
            $table->string('fechaCreacion', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadovacuna');
    }
};
