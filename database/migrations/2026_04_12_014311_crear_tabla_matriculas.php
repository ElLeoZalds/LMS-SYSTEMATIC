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
        Schema::create('matriculas', function (Blueprint $table) {

            $table->id('idmatricula');
            $table->unsignedBigInteger('idcapacitacion');
            $table->unsignedBigInteger('idalumno');
            $table->unsignedBigInteger('idadministrador');
            $table->foreign('idcapacitacion')->references('idcapacitacion')->on('capacitaciones')->onDelete('cascade');
            $table->foreign('idalumno')->references('idusuario')->on('usuarios')->onDelete('restrict');
            $table->foreign('idadministrador')->references('idusuario')->on('usuarios')->onDelete('restrict');
            $table->date('fechamatricula');
            $table->string('becaporcentaje', 5)->nullable();
            $table->char('estado', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matriculas');
    }
};
