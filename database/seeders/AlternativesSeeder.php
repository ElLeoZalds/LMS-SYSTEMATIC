<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternativesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alternatives')->insert([
            // Question 1
            [
                'question_id' => 1,
                'option_text' => 'A cell is the intersection of a row and a column',
                'is_correct' => true
            ],
            [
                'question_id' => 1,
                'option_text' => 'It is a type of chart in Excel',
                'is_correct' => false
            ],
            [
                'question_id' => 1,
                'option_text' => 'It is a Word file',
                'is_correct' => false
            ],

            // Question 2
            [
                'question_id' => 2,
                'option_text' => 'Using the SUM() function or the + symbol',
                'is_correct' => true
            ],
            [
                'question_id' => 2,
                'option_text' => 'Only typing numbers without formulas',
                'is_correct' => false
            ],
            [
                'question_id' => 2,
                'option_text' => 'Inserting images',
                'is_correct' => false
            ],

            // Question 3
            [
                'question_id' => 3,
                'option_text' => 'Includes Word, Excel and PowerPoint for office tasks',
                'is_correct' => true
            ],
            [
                'question_id' => 3,
                'option_text' => 'Only used for graphic design',
                'is_correct' => false
            ],
            [
                'question_id' => 3,
                'option_text' => 'It is an operating system',
                'is_correct' => false
            ],

            // Question 4
            [
                'question_id' => 4,
                'option_text' => 'Software for 2D and 3D technical design and drawing',
                'is_correct' => true
            ],
            [
                'question_id' => 4,
                'option_text' => 'A video editing program',
                'is_correct' => false
            ],

            // Question 5
            [
                'question_id' => 5,
                'option_text' => 'Building Information Modeling',
                'is_correct' => true
            ],
            [
                'question_id' => 5,
                'option_text' => 'An antivirus software',
                'is_correct' => false
            ],

            // Question 6
            [
                'question_id' => 6,
                'option_text' => 'Practice, diction, and control of nerves',
                'is_correct' => true
            ],
            [
                'question_id' => 6,
                'option_text' => 'Just reading slides',
                'is_correct' => false
            ],
        ]);
    }
}