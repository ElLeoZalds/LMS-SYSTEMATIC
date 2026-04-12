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
        Schema::create('asistencias', function (Blueprint $table) {

            $table->id('idasistencia');

            $table->unsignedBigInteger('idhorario');
            $table->unsignedBigInteger('idmatricula');
            $table->foreign('idhorario')->references('idhorario')->on('horarios')->onDelete('cascade');
            $table->foreign('idmatricula')->references('idmatricula')->on('matriculas')->onDelete('cascade');
            $table->string('asistencia', 20);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
