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
        Schema::create('capacitaciones', function (Blueprint $table) {

            $table->id('idcapacitacion');
            $table->unsignedBigInteger('idcurso');
            $table->unsignedBigInteger('idprofesor');
            $table->unsignedBigInteger('idadministrador');
            $table->foreign('idcurso')->references('idcurso')->on('cursos')->onDelete('cascade');
            $table->foreign('idprofesor')->references('idusuario')->on('usuarios')->onDelete('restrict');
            $table->foreign('idadministrador')->references('idusuario')->on('usuarios')->onDelete('restrict');
            $table->string('modalidad', 50);
            $table->decimal('precio', 10, 2);
            $table->date('fechacreacion');
            $table->char('estado', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capacitaciones');
    }
};
