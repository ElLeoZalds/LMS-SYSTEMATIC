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
        Schema::create('personas', function (Blueprint $table) {
            $table->id('idpersona');
            $table->string('apellidos', 20);
            $table->string('nombres', 20);
            $table->string('tipodoc', 20)->nullable();
            $table->string('numdoc', 20)->nullable()->unique();
            $table->string('telefono', 9)->nullable();
            $table->string('direccion', 255)->nullable();
            $table->string('email', 150)->nullable()->unique();
            $table->enum('genero', ['M', 'F'])->nullable();
            $table->date('fechanac')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
