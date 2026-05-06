<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EnrollmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('enrollments')->insert([
            [
                'training_id' => 1,
                'student_id' => 4,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => 10,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 4,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 3,
                'student_id' => 2,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => 5,
                'status' => 'A'
            ],
            [
                'training_id' => 4,
                'student_id' => 3,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 5,
                'student_id' => 4,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => 15,
                'status' => 'A'
            ]
        ]);
    }
}