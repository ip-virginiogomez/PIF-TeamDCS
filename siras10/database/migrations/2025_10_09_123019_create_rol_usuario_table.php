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
        Schema::create('rol_usuario', function (Blueprint $table) {
            $table->id('idRolUsuario');
            $table->string('runUsuario', 10)->nullable();
            $table->unsignedBigInteger('idRol')->nullable();
            $table->timestamps();

            $table->foreign('runUsuario')
                ->references('runUsuario')
                ->on('usuarios')
                ->onUpdate('no action')
                ->onDelete('no action');

            $table->foreign('idRol')
                ->references('idRol')
                ->on('rol')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rol_usuario');
    }
};
