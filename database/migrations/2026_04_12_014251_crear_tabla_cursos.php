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
        Schema::create('cursos', function (Blueprint $table) {

            $table->id('idcurso');
            $table->unsignedBigInteger('idespecialidad');
            $table->foreign('idespecialidad')->references('idespecialidad')->on('especialidades')->onDelete('restrict');
            $table->string('pathbanner')->nullable();
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->integer('cantidadhoras')->nullable();
            $table->decimal('precioreferencial', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cursos');
    }
};
