<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modificar las columnas subject_id y causer_id para que sean VARCHAR
        // Esto es necesario porque el modelo Usuario usa 'runUsuario' (string) como ID

        DB::statement('ALTER TABLE activity_log MODIFY subject_id VARCHAR(255) NULL');
        DB::statement('ALTER TABLE activity_log MODIFY causer_id VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir a BIGINT (esto fallará si hay IDs que no son números, pero es el rollback lógico)
        DB::statement('ALTER TABLE activity_log MODIFY subject_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE activity_log MODIFY causer_id BIGINT UNSIGNED NULL');
    }
};
