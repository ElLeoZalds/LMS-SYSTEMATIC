<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('tasks')->truncate();
        Schema::enableForeignKeyConstraints();

        $trainings = DB::table('trainings')->select('training_id', 'course_id', 'start_date')->get();
        $modulesByCourse = DB::table('modules')->select('id', 'course_id')->get()->groupBy('course_id');
        $tasks = [];

        foreach ($trainings as $training) {
            $modules = $modulesByCourse->get($training->course_id, collect());
            if ($modules->isEmpty()) {
                continue;
            }

            $module = $modules->first();
            $dueDate = now()->parse($training->start_date)->addDays(20);
            $tasks[] = [
                'training_id' => $training->training_id,
                'module_id' => $module->id,
                'title' => 'Tarea práctica del módulo ' . $module->id,
                'description' => 'Actividad práctica propuesta para reforzar los contenidos del módulo.',
                'due_date' => $dueDate->toDateTimeString(),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('tasks')->insert($tasks);
    }
}
