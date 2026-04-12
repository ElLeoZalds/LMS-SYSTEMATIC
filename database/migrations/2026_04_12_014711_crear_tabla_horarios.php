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
        Schema::create('horarios', function (Blueprint $table) {

            $table->id('idhorario');
            $table->unsignedBigInteger('idcapacitacion');
            $table->foreign('idcapacitacion')->references('idcapacitacion')->on('capacitaciones')->onDelete('cascade');
            $table->date('fecha');
            $table->time('inicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
