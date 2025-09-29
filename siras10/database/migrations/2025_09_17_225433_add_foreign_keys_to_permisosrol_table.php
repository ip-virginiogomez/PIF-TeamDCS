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
        Schema::table('permisosrol', function (Blueprint $table) {
            $table->foreign(['idEstadoPermisos'], 'fk_PermisosRol_EstadoPermisos')->references(['idEstadoPermisos'])->on('estadopermisos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idPermisos'], 'fk_PermisosRol_Permisos')->references(['idPermisos'])->on('permisos')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idRol'], 'fk_PermisosRol_Rol')->references(['idRol'])->on('rol')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['runUsuario'], 'fk_PermisosRol_Usuario')->references(['runUsuario'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisosrol', function (Blueprint $table) {
            $table->dropForeign('fk_PermisosRol_EstadoPermisos');
            $table->dropForeign('fk_PermisosRol_Permisos');
            $table->dropForeign('fk_PermisosRol_Rol');
            $table->dropForeign('fk_PermisosRol_Usuario');
        });
    }
};
