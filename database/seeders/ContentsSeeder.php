<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContentsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('contents')->truncate();
        Schema::enableForeignKeyConstraints();

        $trainings = DB::table('trainings')->select('training_id', 'course_id')->get();
        $modulesByCourse = DB::table('modules')->select('id', 'course_id')->get()->groupBy('course_id');
        $contents = [];
        $types = ['video', 'pdf', 'link', 'text'];

        foreach ($trainings as $index => $training) {
            $modules = $modulesByCourse->get($training->course_id, collect());
            if ($modules->isEmpty()) {
                continue;
            }

            $module = $modules->first();
            $contents[] = [
                'training_id' => $training->training_id,
                'module_id' => $module->id,
                'description' => 'Contenido generado para acompañar el módulo.',
                'title' => 'Contenido ' . ($index + 1) . ' del módulo ' . $module->id,
                'type' => $types[$index % count($types)],
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('contents')->insert($contents);
    }
}
