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
        Schema::create('permisos', function (Blueprint $table) {
            $table->id('idPermisos');
            $table->string('nombrePermisos', 45)->nullable();
            $table->unsignedBigInteger('idSubmenu')->nullable();
            $table->date('fechaCreacion')->nullable();
            $table->string('decripcion', 150)->nullable();
            $table->timestamps();

            $table->foreign('idSubmenu')
                ->references('idSubmenu')
                ->on('submenu')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
