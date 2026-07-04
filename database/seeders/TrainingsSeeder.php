<?php

namespace Database\Seeders;

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
            $baseDate = now()->year(2026)->month(1 + ($index % 12))->day(10 + ($index % 15));
            $startDate = $baseDate->copy()->subDays($index % 3 === 0 ? 30 : 15);
            $endDate = $startDate->copy()->addMonths(2)->addDays(10);
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
