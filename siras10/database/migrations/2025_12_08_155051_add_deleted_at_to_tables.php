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
            'usuarios',
            'alumno',
            'centro_formador',
            'centro_salud',
            'carrera',
            'docente',
            'convenio',
            'sede',
            'asignatura',
            'programa',
            'malla_curricular',
            'grupo',
            'cupo_oferta',
            'cupo_distribucion',
            'unidad_clinica',
            'personal',
            'coordinador_campo_clinico',
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
            'usuarios',
            'alumno',
            'centro_formador',
            'centro_salud',
            'carrera',
            'docente',
            'convenio',
            'sede',
            'asignatura',
            'programa',
            'malla_curricular',
            'grupo',
            'cupo_oferta',
            'cupo_distribucion',
            'unidad_clinica',
            'personal',
            'coordinador_campo_clinico',
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
