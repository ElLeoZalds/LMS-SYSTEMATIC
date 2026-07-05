<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TrainingsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('trainings')->truncate();
        Schema::enableForeignKeyConstraints();

        $courseAbbreviations = DB::table('courses')->pluck('abbreviation', 'course_id')->map(fn ($abbreviation) => strtoupper($abbreviation))->all();
        $codeCounters = [];
        $courseIds = DB::table('courses')->pluck('course_id');
        $teacherIds = [2, 3, 4, 5, 6];
        $modalities = ['virtual', 'presential', 'hybrid'];
        $trainings = [];

        foreach ($courseIds as $index => $courseId) {
            $startDate = Carbon::create(2026, 6, 25)->addDays(rand(0, 11));
            $endDate = Carbon::create(2026, 9, 1)->addDays(rand(0, 29));
            $status = $index % 4 === 0 ? 0 : 1;

            $trainings[] = [
                'course_id' => $courseId,
                'teacher_id' => $teacherIds[$index % count($teacherIds)],
                'administrator_id' => 1,
                'modality' => $modalities[$index % count($modalities)],
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($trainings as &$training) {
            $courseId = $training['course_id'];
            $abbreviation = $courseAbbreviations[$courseId] ?? 'COURSE';
            $year = date('Y', strtotime($training['start_date']));
            $counterKey = strtoupper($abbreviation) . '-' . $year;
            $counter = $codeCounters[$counterKey] ?? 0;
            $counter++;
            $codeCounters[$counterKey] = $counter;
            $training['code'] = sprintf('%s-%s-%03d', strtoupper($abbreviation), $year, $counter);
        }

        DB::table('trainings')->insert($trainings);
    }
}
