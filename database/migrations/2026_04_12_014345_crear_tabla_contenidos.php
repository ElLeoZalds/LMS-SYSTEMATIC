<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('contenidos', function (Blueprint $table) {

            $table->id('idcontenido');
            $table->unsignedBigInteger('idcapacitacion');
            $table->foreign('idcapacitacion')->references('idcapacitacion')->on('capacitaciones')->onDelete('cascade');
            $table->string('descripcion')->nullable();
            $table->string('titulo', 150);
            $table->string('tipo', 50);
            $table->integer('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenidos');
    }
};
