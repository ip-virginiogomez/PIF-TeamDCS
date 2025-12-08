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
        $tables = [
            'tipo_practica',
            'tipo_centro_formador',
            'tipo_centro_salud',
            'tipo_personal_salud',
            'tipo_vacuna',
            'estado_vacuna',
            'estado_permisos',
            'menu',
            'submenu',
            'permisos',
            'rol',
            'ciudad',
            'permisos_rol',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (! Schema::hasColumn($table->getTable(), 'deleted_at')) {
                        $table->softDeletes();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'tipo_practica',
            'tipo_centro_formador',
            'tipo_centro_salud',
            'tipo_personal_salud',
            'tipo_vacuna',
            'estado_vacuna',
            'estado_permisos',
            'menu',
            'submenu',
            'permisos',
            'rol',
            'ciudad',
            'permisos_rol',
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($table->getTable(), 'deleted_at')) {
                        $table->dropSoftDeletes();
                    }
                });
            }
        }
    }
};
