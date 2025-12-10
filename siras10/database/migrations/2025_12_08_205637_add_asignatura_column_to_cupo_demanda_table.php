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
        Schema::table('cupo_demanda', function (Blueprint $table) {
            $table->string('asignatura')->nullable()->after('idSedeCarrera');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupo_demanda', function (Blueprint $table) {
            $table->dropColumn('asignatura');
        });
    }
};
