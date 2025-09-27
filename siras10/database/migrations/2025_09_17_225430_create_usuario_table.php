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
        Schema::create('usuario', function (Blueprint $table) {
            $table->string('runUsuario', 10)->primary();
            $table->string('nombreUsuario', 45)->nullable();
            $table->string('correo', 45)->nullable();
            $table->string('contrasenia', 255)->nullable();
            $table->string('fechaCreacion', 45)->nullable();
            $table->integer('idTipoPersonalSalud')->nullable()->index('idtipopersonalsalud_idx');
            $table->string('nombres', 45)->nullable();
            $table->string('apellidoPaterno', 45)->nullable();
            $table->string('apellidoMaterno', 45)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
