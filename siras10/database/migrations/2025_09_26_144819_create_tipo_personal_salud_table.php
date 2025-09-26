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
        Schema::create('tipo_personal_salud', function (Blueprint $table) {
            $table->id('idTipoPersonalSalud');
            $table->string('cargo', 45)->nullable();
            $table->string('descripcion', 150)->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_personal_salud');
    }
};
