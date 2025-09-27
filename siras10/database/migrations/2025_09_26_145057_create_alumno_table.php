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
        Schema::create('alumno', function (Blueprint $table) {
            $table->string('runAlumno', 10)->primary();
            $table->string('nombres', 100)->nullable();
            $table->string('apellidoPaterno', 45)->nullable();
            $table->string('apellidoMaterno', 45)->nullable();
            $table->date('fechaNacto')->nullable();
            $table->binary('foto')->nullable();
            $table->binary('acuerdo')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->string('correo', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno');
    }
};
