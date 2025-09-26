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
        Schema::create('permisos_rol', function (Blueprint $table) {
            $table->id('idPermisosRol');
            $table->unsignedBigInteger('idPermisos')->nullable();
            $table->unsignedBigInteger('idRol')->nullable();
            $table->unsignedBigInteger('idEstadoPermisos')->nullable();
            $table->string('runUsuario', 10)->nullable();
            $table->timestamps();

            $table->foreign('idPermisos')
                ->references('idPermisos')
                ->on('permisos')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idRol')
                ->references('idRol')
                ->on('rol')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idEstadoPermisos')
                ->references('idEstadoPermisos')
                ->on('estado_permisos')
                ->onUpdate('no action')
                ->onDelete('no action');
                
            $table->foreign('runUsuario')
                ->references('runUsuario')
                ->on('usuarios')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos_rol');
    }
};
