<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('questions')->insert([
            [
                'assessment_id' => 1,
                'question_text' => '¿Qué es una celda en Excel?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 1,
                'question_text' => '¿Cómo se realiza una suma en Excel?',
                'score' => 10,
                'order_index' => 2
            ],
            [
                'assessment_id' => 2,
                'question_text' => '¿Qué herramientas incluye el software de oficina?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 3,
                'question_text' => '¿Para qué se utiliza AutoCAD?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 4,
                'question_text' => '¿Qué es el modelado BIM en Revit?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 5,
                'question_text' => '¿Qué técnicas mejoran la oratoria?',
                'score' => 10,
                'order_index' => 1
            ]
        ]);
    }
}