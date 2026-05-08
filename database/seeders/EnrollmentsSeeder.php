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
            ],
            // Nuevos enrollments para training 1 (10 estudiantes)
            [
                'training_id' => 1,
                'student_id' => 5,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 6,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 7,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 8,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 9,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 10,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 11,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 12,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 1,
                'student_id' => 13,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            // Training 2 (11 estudiantes)
            [
                'training_id' => 2,
                'student_id' => 14,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 15,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 16,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 17,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 18,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 19,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 20,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 21,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 22,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 23,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 2,
                'student_id' => 24,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            // Training 3 (3 estudiantes)
            [
                'training_id' => 3,
                'student_id' => 25,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ],
            [
                'training_id' => 3,
                'student_id' => 26,
                'administrator_id' => 1,
                'enrollment_date' => now(),
                'scholarship_percentage' => null,
                'status' => 'A'
            ]
        ]);
    }
}