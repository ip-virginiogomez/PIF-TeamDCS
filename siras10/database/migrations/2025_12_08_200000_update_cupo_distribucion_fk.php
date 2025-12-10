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
        Schema::table('cupo_distribucion', function (Blueprint $table) {
            // Drop foreign key and column for idSedeCarrera
            $table->dropForeign(['idSedeCarrera']);
            $table->dropColumn('idSedeCarrera');

            // Add new column and foreign key for idDemandaCupo
            $table->unsignedBigInteger('idDemandaCupo')->nullable()->after('idCupoOferta');
            $table->foreign('idDemandaCupo')->references('idDemandaCupo')->on('cupo_demanda');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupo_distribucion', function (Blueprint $table) {
            // Drop new foreign key and column
            $table->dropForeign(['idDemandaCupo']);
            $table->dropColumn('idDemandaCupo');

            // Restore old column and foreign key
            $table->unsignedBigInteger('idSedeCarrera')->nullable()->after('idCupoOferta');
            $table->foreign('idSedeCarrera')
                ->references('idSedeCarrera')
                ->on('sede_carrera')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }
};
