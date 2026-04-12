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
        Schema::create('alternativas', function (Blueprint $table) {

            $table->id('idalternativa');
            $table->unsignedBigInteger('idpregunta');
            $table->foreign('idpregunta')->references('idpregunta')->on('preguntas')->onDelete('cascade');
            $table->text('textoopcion');
            $table->boolean('respuestacorrecta')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alternativas');
    }
};
