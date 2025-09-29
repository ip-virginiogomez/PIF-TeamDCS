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
        Schema::create('estadopermisos', function (Blueprint $table) {
            $table->integer('idEstadoPermisos')->primary();
            $table->string('nombreEstado', 45)->nullable();
            $table->string('descripcion', 100)->nullable();
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estadopermisos');
    }
};
