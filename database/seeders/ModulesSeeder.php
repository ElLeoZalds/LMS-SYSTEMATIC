<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ModulesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('modules')->truncate();
        Schema::enableForeignKeyConstraints();

        $courses = DB::table('courses')->select('course_id', 'title')->get();
        $modules = [];

        foreach ($courses as $index => $course) {
            $moduleCount = $index % 3 === 0 ? 4 : 3;
            $baseTitle = preg_replace('/\s+/', ' ', trim($course->title));

            for ($i = 1; $i <= $moduleCount; $i++) {
                $title = match ($i) {
                    1 => 'Fundamentos de ' . $baseTitle,
                    2 => 'Práctica y ejercicios de ' . $baseTitle,
                    3 => 'Aplicaciones reales de ' . $baseTitle,
                    default => 'Proyecto final de ' . $baseTitle,
                };

                $modules[] = [
                    'course_id' => $course->course_id,
                    'title' => $title,
                    'description' => 'Módulo generado automáticamente para ' . $baseTitle . '.',
                    'order' => $i,
                    'is_active' => $i < 4,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('modules')->insert($modules);
    }
}
