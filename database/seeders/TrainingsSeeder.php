<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrainingsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('trainings')->insert([
            [
                'course_id' => 1,
                'teacher_id' => 2,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'price' => 220.00,
                'creation_date' => now(),
                'status' => 'A'
            ],
            [
                'course_id' => 2,
                'teacher_id' => 3,
                'administrator_id' => 1,
                'modality' => 'presential',
                'price' => 180.00,
                'creation_date' => now(),
                'status' => 'A'
            ],
            [
                'course_id' => 3,
                'teacher_id' => 2,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'price' => 280.00,
                'creation_date' => now(),
                'status' => 'A'
            ],
            [
                'course_id' => 4,
                'teacher_id' => 3,
                'administrator_id' => 1,
                'modality' => 'presential',
                'price' => 300.00,
                'creation_date' => now(),
                'status' => 'A'
            ],
            [
                'course_id' => 5,
                'teacher_id' => 2,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'price' => 140.00,
                'creation_date' => now(),
                'status' => 'A'
            ],
            [
                'course_id' => 6,
                'teacher_id' => 3,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'price' => 260.00,
                'creation_date' => now(),
                'status' => 'A'
            ]
        ]);
    }
}