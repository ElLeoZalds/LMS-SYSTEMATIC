<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            // Clave primaria
            $table->id('task_id'); 
            
            // Relación con trainings (restricción de llave foránea)
            $table->unsignedBigInteger('training_id');
            $table->foreign('training_id')
                  ->references('training_id')
                  ->on('trainings')
                  ->onDelete('cascade');

            // Campos de la tarea
            $table->string('title', 150);
            $table->text('description')->nullable();
            $table->dateTime('due_date'); // Fecha de entrega
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};