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
        Schema::create('convenio', function (Blueprint $table) {
            $table->id('idConvenio');
            $table->binary('documento')->nullable();
            $table->unsignedBigInteger('idCentroFormador')->nullable();
            $table->date('fechaSubida')->nullable();
            $table->integer('anioValidez')->nullable();
            $table->timestamps();

            $table->foreign('idCentroFormador')
                ->references('idCentroFormador')
                ->on('centro_formador')
                ->onUpdate('no action')
                ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenio');
    }
};
