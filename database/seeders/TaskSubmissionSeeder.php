<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskSubmission;

class TaskSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        // Simulamos la entrega de un alumno para la tarea con task_id = 1
        TaskSubmission::create([
            'task_id' => 1,
            'student_id' => 3, // ID del estudiante de prueba
            'submission_text' => 'Profesor, adjunto el enlace de mi repositorio de GitHub con el entorno configurado: https://github.com/alumno/lms-test',
            'file_path' => 'submissions/laboratorio1_alumno3.pdf',
            'submitted_at' => now()->subDays(2),
            'grade' => null,             // Pendiente de calificar por el docente
            'teacher_feedback' => null,  // Sin comentarios aún
            'graded_at' => null,
        ]);

        // Simulamos otra entrega ya calificada para ver cómo se comporta el sistema
        TaskSubmission::create([
            'task_id' => 1,
            'student_id' => 4, // Otro estudiante de prueba
            'submission_text' => 'Aquí está mi tarea cargada a tiempo.',
            'file_path' => 'submissions/laboratorio1_alumno4.pdf',
            'submitted_at' => now()->subDays(3),
            'grade' => 18.50, // Ya calificado por el docente
            'teacher_feedback' => 'Excelente implementación, el servidor local responde correctamente en Kubuntu.',
            'graded_at' => now()->subDay(),
        ]);
    }
}