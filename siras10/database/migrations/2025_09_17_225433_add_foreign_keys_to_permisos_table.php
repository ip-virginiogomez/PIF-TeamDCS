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
        Schema::table('permisos', function (Blueprint $table) {
            $table->foreign(['idSubmenu'], 'fk_Permisos_Submenu')->references(['idSubmenu'])->on('submenu')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permisos', function (Blueprint $table) {
            $table->dropForeign('fk_Permisos_Submenu');
        });
    }
};
