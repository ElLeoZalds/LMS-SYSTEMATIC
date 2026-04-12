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
        Schema::create('pagos', function (Blueprint $table) {

            $table->id('idpago');
            $table->unsignedBigInteger('idmatricula');
            $table->unsignedBigInteger('idmetodo');
            $table->foreign('idmatricula')->references('idmatricula')->on('matriculas')->onDelete('cascade');
            $table->foreign('idmetodo')->references('idmetodo')->on('metodospagos')->onDelete('restrict');
            $table->date('fecha');
            $table->decimal('amortizacion', 10, 2);
            $table->decimal('monto', 10, 2);
            $table->char('estado', 1)->default('A');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
