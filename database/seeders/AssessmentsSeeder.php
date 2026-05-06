<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assessments')->insert([
            [
                'training_id' => 1,
                'title' => 'Basic Excel Assessment',
                'description' => 'Basic Excel knowledge evaluation',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 2,
                'title' => 'Office Suite Assessment',
                'description' => 'Evaluation of office tools',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 3,
                'title' => 'AutoCAD Assessment',
                'description' => 'Technical design evaluation in AutoCAD',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 4,
                'title' => 'Revit Assessment',
                'description' => 'BIM modeling evaluation in Revit',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 5,
                'title' => 'Public Speaking Assessment',
                'description' => 'Oral communication skills evaluation',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}