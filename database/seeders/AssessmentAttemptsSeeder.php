<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class AssessmentAttemptsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('assessment_attempts')->truncate();
        Schema::enableForeignKeyConstraints();

        // Get enrollments for finished trainings (1, 2, 3)
        $enrollments = DB::table('enrollments')
            ->whereIn('training_id', [1, 2, 3])
            ->get();

        $attempts = [];

        foreach ($enrollments as $enrollment) {
            // Get assessments for this training
            $assessments = DB::table('assessments')
                ->where('training_id', $enrollment->training_id)
                ->get();

            foreach ($assessments as $assessment) {
                // Determine if this student passes or fails based on student_id parity
                $passes = ($enrollment->student_id % 2 === 0);

                if ($passes) {
                    // Passed score: 14 to 20
                    $score = 14 + (($enrollment->student_id * 3) % 7);
                } else {
                    // Failed score: 5 to 10
                    $score = 5 + (($enrollment->student_id * 2) % 6);
                    $score = min($score, 10);
                }

                $attempts[] = [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'assessment_id' => $assessment->assessment_id,
                    'number' => 1,
                    'date' => Carbon::parse($enrollment->enrollment_date)->addDays(10)->toDateString(),
                    'score' => $score,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('assessment_attempts')->insert($attempts);
    }
}
