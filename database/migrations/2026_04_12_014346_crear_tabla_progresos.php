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
        Schema::create('progresos', function (Blueprint $table) {

            $table->id('idprogreso');   
            $table->unsignedBigInteger('idmatricula');
            $table->unsignedBigInteger('idcontenido');
            $table->foreign('idmatricula')->references('idmatricula')->on('matriculas')->onDelete('cascade');
            $table->foreign('idcontenido')->references('idcontenido')->on('contenidos')->onDelete('cascade');
            $table->decimal('porcentaje', 5, 2);
            $table->date('fechaactividad');
            $table->char('estado', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progresos');
    }
};
