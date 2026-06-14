<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AssessmentAttemptsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('assessment_attempts')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('assessment_attempts')->insert([
            [
                'enrollment_id' => 1,
                'assessment_id' => 1,
                'number' => 1,
                'date' => now()->subDays(19)->toDateString(),
                'score' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 2,
                'assessment_id' => 2,
                'number' => 1,
                'date' => now()->subDays(18)->toDateString(),
                'score' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 3,
                'assessment_id' => 3,
                'number' => 1,
                'date' => now()->subDays(17)->toDateString(),
                'score' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 4,
                'assessment_id' => 4,
                'number' => 1,
                'date' => now()->subDays(16)->toDateString(),
                'score' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 5,
                'assessment_id' => 5,
                'number' => 1,
                'date' => now()->subDays(15)->toDateString(),
                'score' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 6,
                'assessment_id' => 6,
                'number' => 1,
                'date' => now()->subDays(14)->toDateString(),
                'score' => 16,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 7,
                'assessment_id' => 7,
                'number' => 1,
                'date' => now()->subDays(13)->toDateString(),
                'score' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 8,
                'assessment_id' => 8,
                'number' => 1,
                'date' => now()->subDays(12)->toDateString(),
                'score' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 9,
                'assessment_id' => 9,
                'number' => 1,
                'date' => now()->subDays(11)->toDateString(),
                'score' => 19,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 10,
                'assessment_id' => 10,
                'number' => 1,
                'date' => now()->subDays(10)->toDateString(),
                'score' => 20,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 11,
                'assessment_id' => 11,
                'number' => 1,
                'date' => now()->subDays(9)->toDateString(),
                'score' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 12,
                'assessment_id' => 12,
                'number' => 1,
                'date' => now()->subDays(8)->toDateString(),
                'score' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 13,
                'assessment_id' => 13,
                'number' => 1,
                'date' => now()->subDays(7)->toDateString(),
                'score' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 14,
                'assessment_id' => 14,
                'number' => 1,
                'date' => now()->subDays(6)->toDateString(),
                'score' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 15,
                'assessment_id' => 15,
                'number' => 1,
                'date' => now()->subDays(5)->toDateString(),
                'score' => 14,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 16,
                'assessment_id' => 16,
                'number' => 1,
                'date' => now()->subDays(4)->toDateString(),
                'score' => 15,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 17,
                'assessment_id' => 17,
                'number' => 1,
                'date' => now()->subDays(3)->toDateString(),
                'score' => 16,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 18,
                'assessment_id' => 18,
                'number' => 1,
                'date' => now()->subDays(2)->toDateString(),
                'score' => 17,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 19,
                'assessment_id' => 19,
                'number' => 1,
                'date' => now()->subDays(1)->toDateString(),
                'score' => 18,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 20,
                'assessment_id' => 20,
                'number' => 1,
                'date' => now()->subDays(0)->toDateString(),
                'score' => 19,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
