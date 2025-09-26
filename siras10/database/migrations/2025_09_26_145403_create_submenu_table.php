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
        Schema::create('submenu', function (Blueprint $table) {
            $table->id('idSubmenu');
            $table->string('nombreSubmenu', 45)->nullable();
            $table->unsignedBigInteger('idMenu')->nullable();
            $table->timestamps();

            $table->foreign('idMenu')
              ->references('idMenu')
              ->on('menu')
              ->onUpdate('no action')
              ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submenu');
    }
};
