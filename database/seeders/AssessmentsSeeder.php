<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AssessmentsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('assessments')->truncate();
        Schema::enableForeignKeyConstraints();

        $trainings = DB::table('trainings')->select('training_id', 'course_id', 'start_date', 'end_date')->get();
        $modulesByCourse = DB::table('modules')->select('id', 'course_id')->get()->groupBy('course_id');
        $assessments = [];

        foreach ($trainings as $training) {
            $modules = $modulesByCourse->get($training->course_id, collect());
            if ($modules->isEmpty()) {
                continue;
            }

            $module = $modules->first();

            $startDate = now()->parse($training->start_date)->subDays(7);
            $endDate = now()->parse($training->end_date)->subDays(3);
            $assessments[] = [
                'training_id' => $training->training_id,
                'module_id' => $module->id,
                'title' => 'Evaluación de módulo ' . $module->id,
                'description' => 'Evaluación generada para validar el progreso del módulo.',
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'allowed_attempts' => 2,
                'time_limit' => 60,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('assessments')->insert($assessments);
    }
}
