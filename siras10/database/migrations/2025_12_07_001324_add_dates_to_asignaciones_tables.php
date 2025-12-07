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
        Schema::table('coordinador_campo_clinico', function (Blueprint $table) {
            $table->date('fechaInicio')->nullable()->after('fechaCreacion');
            $table->date('fechaFin')->nullable()->after('fechaInicio');
        });

        Schema::table('personal', function (Blueprint $table) {
            $table->date('fechaInicio')->nullable()->after('fechaCreacion');
            $table->date('fechaFin')->nullable()->after('fechaInicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coordinador_campo_clinico', function (Blueprint $table) {
            $table->dropColumn(['fechaInicio', 'fechaFin']);
        });

        Schema::table('personal', function (Blueprint $table) {
            $table->dropColumn(['fechaInicio', 'fechaFin']);
        });
    }
};
