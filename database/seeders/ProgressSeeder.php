<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('progress')->insert([
            [
                'enrollment_id' => 1,
                'content_id' => 1,
                'percentage' => 20.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 1,
                'content_id' => 2,
                'percentage' => 40.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 2,
                'content_id' => 3,
                'percentage' => 30.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 3,
                'content_id' => 4,
                'percentage' => 50.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 4,
                'content_id' => 5,
                'percentage' => 60.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 5,
                'content_id' => 6,
                'percentage' => 80.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            // Nuevo progreso para enrollment 6 (training 1, content 1)
            [
                'enrollment_id' => 6,
                'content_id' => 1,
                'percentage' => 100.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 6,
                'content_id' => 2,
                'percentage' => 80.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 7,
                'content_id' => 1,
                'percentage' => 50.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 15,
                'content_id' => 3,
                'percentage' => 90.00,
                'activity_date' => now(),
                'status' => 'A'
            ],
            [
                'enrollment_id' => 16,
                'content_id' => 3,
                'percentage' => 60.00,
                'activity_date' => now(),
                'status' => 'A'
            ]
        ]);
    }
}