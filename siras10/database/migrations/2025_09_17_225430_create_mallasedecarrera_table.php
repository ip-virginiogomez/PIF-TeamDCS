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
        Schema::create('mallasedecarrera', function (Blueprint $table) {
            $table->integer('idMallaSedeCarrera')->primary();
            $table->date('fechaSubida')->nullable();
            $table->binary('doc')->nullable();
            $table->integer('anio')->nullable();
            $table->integer('idMallaCurricular')->nullable()->index('idmallacurricular_idx');
            $table->integer('idSedeCarrera')->nullable()->index('idsedecarrera_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mallasedecarrera');
    }
};
