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
        Schema::create('intentosevaluaciones', function (Blueprint $table) {

            $table->id('idintento');

            $table->unsignedBigInteger('idmatricula');
            $table->unsignedBigInteger('idevaluacion');
            $table->foreign('idmatricula')->references('idmatricula')->on('matriculas')->onDelete('cascade');
            $table->foreign('idevaluacion')->references('idevaluacion')->on('evaluaciones')->onDelete('cascade');
            $table->integer('numero');
            $table->date('fecha');
            $table->decimal('puntaje', 5, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intentosevaluaciones');
    }
};
