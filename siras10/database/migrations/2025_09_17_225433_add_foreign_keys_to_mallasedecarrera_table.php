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
        Schema::table('mallasedecarrera', function (Blueprint $table) {
            $table->foreign(['idMallaCurricular'], 'fk_MallaSedeCarrera_MallaCurricular')->references(['idMallaCurricular'])->on('mallacurricular')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['idSedeCarrera'], 'fk_MallaSedeCarrera_SedeCarrera')->references(['idSedeCarrera'])->on('sedecarrera')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mallasedecarrera', function (Blueprint $table) {
            $table->dropForeign('fk_MallaSedeCarrera_MallaCurricular');
            $table->dropForeign('fk_MallaSedeCarrera_SedeCarrera');
        });
    }
};
