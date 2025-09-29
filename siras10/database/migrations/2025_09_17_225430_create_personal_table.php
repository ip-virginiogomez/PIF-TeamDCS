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
        Schema::create('personal', function (Blueprint $table) {
            $table->integer('idPersonal')->primary();
            $table->integer('idCentroSalud')->nullable()->index('idcentrosalud_idx');
            $table->string('runUsuario', 10)->nullable()->index('idusuario_idx');
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal');
    }
};
