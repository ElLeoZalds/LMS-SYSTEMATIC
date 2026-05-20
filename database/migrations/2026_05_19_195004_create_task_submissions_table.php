<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id('submission_id');

            // Relación con la tarea (tasks)
            $table->unsignedBigInteger('task_id');
            $table->foreign('task_id')->references('task_id')->on('tasks')->onDelete('cascade');

            // Relación con el alumno (users)
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('user_id')->on('users')->onDelete('cascade');

            // Datos de la entrega del alumno
            $table->text('submission_text')->nullable(); // Enlaces o texto plano
            $table->string('file_path')->nullable();      // Ruta del archivo PDF/ZIP en storage
            $table->dateTime('submitted_at');

            // Datos de la revisión del Docente
            $table->decimal('grade', 5, 2)->nullable();  // Nota (ej: 18.50)
            $table->text('teacher_feedback')->nullable(); // Retroalimentación del profesor
            $table->dateTime('graded_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};