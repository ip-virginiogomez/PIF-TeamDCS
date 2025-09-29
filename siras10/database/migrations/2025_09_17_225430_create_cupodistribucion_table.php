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
        Schema::create('cupodistribucion', function (Blueprint $table) {
            $table->integer('idCupoDistribucion')->primary();
            $table->integer('idCupoOferta')->nullable()->index('idcupooferta_idx');
            $table->integer('idSedeCarrera')->nullable()->index('idsedecarrera_idx');
            $table->integer('cantCupos')->nullable();
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cupodistribucion');
    }
};
