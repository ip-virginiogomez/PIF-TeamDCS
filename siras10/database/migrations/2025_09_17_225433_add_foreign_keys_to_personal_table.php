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
        Schema::table('personal', function (Blueprint $table) {
            $table->foreign(['idCentroSalud'], 'fk_Personal_CentroSalud')->references(['idCentroSalud'])->on('centrosalud')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['runUsuario'], 'fk_Personal_Usuario')->references(['runUsuario'])->on('usuario')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal', function (Blueprint $table) {
            $table->dropForeign('fk_Personal_CentroSalud');
            $table->dropForeign('fk_Personal_Usuario');
        });
    }
};
