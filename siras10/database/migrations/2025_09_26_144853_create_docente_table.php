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
        Schema::create('docente', function (Blueprint $table) {
            $table->string('runDocente', 10)->primary();
            $table->string('nombresDocente', 45)->nullable();
            $table->string('apellidoPaterno', 45)->nullable();
            $table->string('apellidoMaterno', 45)->nullable();
            $table->string('correo', 45)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->date('fechaNacto')->nullable();
            $table->string('profesion', 45)->nullable();
            $table->binary('foto')->nullable();
            $table->binary('certSuperInt')->nullable();
            $table->binary('curriculum')->nullable();
            $table->binary('certRCP')->nullable();
            $table->binary('certIAAS')->nullable();
            $table->binary('acuerdo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('docente');
    }
};
