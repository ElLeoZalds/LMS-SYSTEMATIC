<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class ProgressSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('progress')->truncate();
        Schema::enableForeignKeyConstraints();

        $enrollments = DB::table('enrollments')->get();
        $progressData = [];

        foreach ($enrollments as $enrollment) {
            // Get contents for this training
            $contents = DB::table('contents')->where('training_id', $enrollment->training_id)->get();

            foreach ($contents as $content) {
                $isFinished = in_array($enrollment->training_id, [1, 2, 3]);

                if ($isFinished) {
                    $percentage = 100.00;
                    $activityDate = Carbon::parse($enrollment->enrollment_date)->addDays(5)->toDateString();
                } else {
                    // For active courses, let's randomize or use a formula based on enrollment id
                    $percentage = (($enrollment->enrollment_id * 7 + $content->content_id * 13) % 19) * 5;
                    $activityDate = Carbon::parse($enrollment->enrollment_date)->addDays(2)->toDateString();
                }

                $progressData[] = [
                    'enrollment_id' => $enrollment->enrollment_id,
                    'content_id' => $content->content_id,
                    'percentage' => $percentage,
                    'activity_date' => $activityDate,
                    'status' => 'A',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Chunk insert to avoid database limit issues
        foreach (array_chunk($progressData, 100) as $chunk) {
            DB::table('progress')->insert($chunk);
        }
    }
}