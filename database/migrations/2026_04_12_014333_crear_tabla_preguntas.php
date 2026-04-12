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
        Schema::create('preguntas', function (Blueprint $table) {

            $table->id('idpregunta');
            $table->unsignedBigInteger('idevaluacion');
            $table->foreign('idevaluacion')->references('idevaluacion')->on('evaluaciones')->onDelete('cascade');
            $table->text('textopregunta');
            $table->integer('puntaje');
            $table->integer('orden');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preguntas');
    }
};
