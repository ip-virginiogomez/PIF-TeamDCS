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
        Schema::table('submenu', function (Blueprint $table) {
            $table->foreign(['idMenu'], 'fk_Submenu_Menu')->references(['idMenu'])->on('menu')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submenu', function (Blueprint $table) {
            $table->dropForeign('fk_Submenu_Menu');
        });
    }
};
