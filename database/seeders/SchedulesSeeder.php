<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('schedules')->delete();

        $timeSlots = [
            ['start' => '09:00:00', 'end' => '11:00:00'],
            ['start' => '14:00:00', 'end' => '16:00:00'],
            ['start' => '10:00:00', 'end' => '12:00:00'],
            ['start' => '16:00:00', 'end' => '18:00:00'],
            ['start' => '08:30:00', 'end' => '10:30:00'],
        ];

        $schedules = [];

        foreach ([1, 2, 3, 4, 5, 6] as $index => $trainingId) {
            $startDay = rand(1, 31);
            $openDate = 
                \Carbon\Carbon::create(2026, 5, $startDay)->toDateString();
            $closeDate = \Carbon\Carbon::create(2026, 5, $startDay)
                ->addMonths(rand(2, 3))
                ->toDateString();
            $slot = $timeSlots[$index % count($timeSlots)];

            $schedules[] = [
                'training_id' => $trainingId,
                'date' => $openDate,
                'start_time' => $slot['start'],
                'end_time' => $slot['end'],
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $schedules[] = [
                'training_id' => $trainingId,
                'date' => $closeDate,
                'start_time' => $slot['start'],
                'end_time' => $slot['end'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('schedules')->insert($schedules);
    }
}