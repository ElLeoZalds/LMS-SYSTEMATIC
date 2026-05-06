<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contents')->insert([
            [
                'training_id' => 1,
                'description' => 'Introduction to Excel and working environment',
                'title' => 'Excel Fundamentals',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 1,
                'description' => 'Basic functions and formulas',
                'title' => 'Basic Formulas',
                'type' => 'Document',
                'order_index' => 2
            ],
            [
                'training_id' => 2,
                'description' => 'Word, Excel and PowerPoint tools',
                'title' => 'Basic Office Suite',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 3,
                'description' => 'Introduction to technical design',
                'title' => 'AutoCAD Basics',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 4,
                'description' => 'BIM architectural modeling',
                'title' => 'Revit Introduction',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 5,
                'description' => 'Oral communication techniques',
                'title' => 'Basic Public Speaking',
                'type' => 'Document',
                'order_index' => 1
            ],
            [
                'training_id' => 6,
                'description' => 'Graphic design tools',
                'title' => 'Intro to Graphic Design',
                'type' => 'Video',
                'order_index' => 1
            ]
        ]);
    }
}