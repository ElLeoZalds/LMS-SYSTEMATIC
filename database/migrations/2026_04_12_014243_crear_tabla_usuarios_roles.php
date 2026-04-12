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
        Schema::create('usuariosroles', function (Blueprint $table) {

            $table->unsignedBigInteger('idusuario');
            $table->unsignedBigInteger('idrol');
            $table->foreign('idusuario')->references('idusuario')->on('usuarios')->onDelete('cascade');
            $table->foreign('idrol')->references('idrol')->on('roles')->onDelete('cascade');
            $table->primary(['idusuario', 'idrol']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuariosroles');
    }
};
