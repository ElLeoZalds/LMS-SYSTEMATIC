<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class TaskSubmissionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('task_submissions')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get enrollments for finished trainings (1, 2, 3)
        $enrollments = DB::table('enrollments')
            ->whereIn('training_id', [1, 2, 3])
            ->get();

        $submissions = [];

        foreach ($enrollments as $enrollment) {
            // Get tasks for this training
            $tasks = DB::table('tasks')
                ->where('training_id', $enrollment->training_id)
                ->get();

            foreach ($tasks as $task) {
                // Determine if this student passes or fails based on student_id parity
                $passes = ($enrollment->student_id % 2 === 0);

                if ($passes) {
                    // Passed grade: 13 to 19
                    $grade = 13 + (($enrollment->student_id * 4) % 7);
                    $feedback = 'Excelente trabajo. Sigue así.';
                } else {
                    // Failed grade: 4 to 9.5
                    $grade = 4 + (($enrollment->student_id * 3) % 6);
                    $feedback = 'Falta desarrollar más los puntos clave de la tarea.';
                }

                $submissions[] = [
                    'task_id' => $task->task_id,
                    'student_id' => $enrollment->student_id,
                    'submission_text' => 'Enlace al proyecto de la tarea: https://github.com/student/project-' . $task->task_id,
                    'file_path' => null,
                    'submitted_at' => Carbon::parse($enrollment->enrollment_date)->addDays(8)->toDateTimeString(),
                    'grade' => $grade,
                    'teacher_feedback' => $feedback,
                    'graded_at' => Carbon::parse($enrollment->enrollment_date)->addDays(9)->toDateTimeString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('task_submissions')->insert($submissions);
    }
}
