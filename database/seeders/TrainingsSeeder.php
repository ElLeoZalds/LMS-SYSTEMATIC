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
                'start_date' => '2026-05-22',
                'end_date' => '2026-06-22',
                'status' => 1,
            ],
            [
                'course_id' => 2,
                'teacher_id' => 3,
                'administrator_id' => 1,
                'modality' => 'presential',
                'start_date' => '2026-05-25',
                'end_date' => '2026-07-10',
                'status' => 1,
            ],
            [
                'course_id' => 3,
                'teacher_id' => 2,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'start_date' => '2026-06-01',
                'end_date' => '2026-07-15',
                'status' => 1,
            ],
            [
                'course_id' => 4,
                'teacher_id' => 3,
                'administrator_id' => 1,
                'modality' => 'presential',
                'start_date' => '2026-06-05',
                'end_date' => '2026-08-05',
                'status' => 1,
            ],
            [
                'course_id' => 5,
                'teacher_id' => 2,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'start_date' => '2026-06-10',
                'end_date' => '2026-07-20',
                'status' => 1,
            ],
            [
                'course_id' => 6,
                'teacher_id' => 3,
                'administrator_id' => 1,
                'modality' => 'virtual',
                'start_date' => '2026-06-12',
                'end_date' => '2026-08-12',
                'status' => 1,
            ]
        ]);
    }
}