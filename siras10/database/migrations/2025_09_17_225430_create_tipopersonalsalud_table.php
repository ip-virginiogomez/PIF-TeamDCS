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
        Schema::create('tipopersonalsalud', function (Blueprint $table) {
            $table->integer('idTipoPersonalSalud')->primary();
            $table->string('cargo', 45)->nullable();
            $table->string('descipcion', 150)->nullable();
            $table->date('fechaCreacion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipopersonalsalud');
    }
};
