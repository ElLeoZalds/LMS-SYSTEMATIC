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
        Schema::create('profesorespecialidades', function (Blueprint $table) {

            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idespecialidad');
            $table->foreign('idusuario')->references('idusuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('idespecialidad')->references('idespecialidad')->on('especialidades')->onDelete('cascade');
            $table->primary(['idusuario', 'idespecialidad']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profesorespecialidades');
    }
};
