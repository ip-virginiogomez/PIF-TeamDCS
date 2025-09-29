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
        Schema::create('programa', function (Blueprint $table) {
            $table->id('idPrograma');
            $table->unsignedBigInteger('idAsignatura')->nullable();
            $table->binary('documento')->nullable();
            $table->date('fechaSubida')->nullable();
            $table->timestamps();

            $table->foreign('idAsignatura')
                ->references('idAsignatura')
                ->on('asignatura')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programa');
    }
};
