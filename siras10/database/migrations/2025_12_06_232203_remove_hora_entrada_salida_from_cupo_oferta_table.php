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
        Schema::table('cupo_oferta', function (Blueprint $table) {
            $table->dropColumn(['horaEntrada', 'horaSalida']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cupo_oferta', function (Blueprint $table) {
            $table->time('horaEntrada')->nullable();
            $table->time('horaSalida')->nullable();
        });
    }
};
