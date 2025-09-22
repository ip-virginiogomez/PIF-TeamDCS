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
        Schema::create('permisosrol', function (Blueprint $table) {
            $table->integer('idPermisosRol')->primary();
            $table->integer('idPermisos')->nullable()->index('idpermisos_idx');
            $table->integer('idRol')->nullable()->index('idrol_idx');
            $table->integer('idEstadoPermisos')->nullable()->index('idestadopermisos_idx');
            $table->string('runUsuario', 10)->nullable()->index('runusuario_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisosrol');
    }
};
