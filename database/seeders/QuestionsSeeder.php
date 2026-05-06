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
                'question_text' => 'What is a cell in Excel?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 1,
                'question_text' => 'How do you perform a sum in Excel?',
                'score' => 10,
                'order_index' => 2
            ],
            [
                'assessment_id' => 2,
                'question_text' => 'What tools are included in office software?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 3,
                'question_text' => 'What is AutoCAD used for?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 4,
                'question_text' => 'What is BIM modeling in Revit?',
                'score' => 10,
                'order_index' => 1
            ],
            [
                'assessment_id' => 5,
                'question_text' => 'What techniques improve public speaking?',
                'score' => 10,
                'order_index' => 1
            ]
        ]);
    }
}