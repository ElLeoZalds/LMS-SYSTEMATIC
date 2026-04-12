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
        Schema::create('evaluaciones', function (Blueprint $table) {

            $table->id('idevaluacion');
            $table->unsignedBigInteger('idcapacitacion');
            $table->foreign('idcapacitacion')->references('idcapacitacion')->on('capacitaciones')->onDelete('cascade');
            $table->string('titulo', 150);
            $table->text('descripcion')->nullable();
            $table->date('fechainicio');
            $table->date('fechafin');
            $table->integer('intentospermitidos');
            $table->boolean('activo')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones');
    }
};
