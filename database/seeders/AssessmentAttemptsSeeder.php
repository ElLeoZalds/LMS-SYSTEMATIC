<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentAttemptsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assessment_attempts')->insert([
            [
                'enrollment_id' => 1,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now(),
                'score' => 18.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 2,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now(),
                'score' => 16.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 3,
                'assessment_id' => 3,
                'number' => 1,
                'date' => now(),
                'score' => 19.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 4,
                'assessment_id' => 4,
                'number' => 1,
                'date' => now(),
                'score' => 15.75,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 5,
                'assessment_id' => 5,
                'number' => 1,
                'date' => now(),
                'score' => 17.25,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Nuevos intentos para assessment 1
            [
                'enrollment_id' => 6,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now(),
                'score' => 19.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 7,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now(),
                'score' => 17.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 8,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now(),
                'score' => 16.25,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 9,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now(),
                'score' => 18.75,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 10,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now(),
                'score' => 15.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Assessment 2
            [
                'enrollment_id' => 15,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now(),
                'score' => 18.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 16,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now(),
                'score' => 17.50,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 17,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now(),
                'score' => 19.25,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 18,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now(),
                'score' => 16.75,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'enrollment_id' => 19,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now(),
                'score' => 18.50,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}