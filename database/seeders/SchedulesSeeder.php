<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchedulesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('schedules')->insert([
            [
                'training_id' => 1,
                'date' => now()->addDays(1)->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '11:00:00'
            ],
            [
                'training_id' => 2,
                'date' => now()->addDays(2)->toDateString(),
                'start_time' => '14:00:00',
                'end_time' => '16:00:00'
            ],
            [
                'training_id' => 3,
                'date' => now()->addDays(3)->toDateString(),
                'start_time' => '10:00:00',
                'end_time' => '12:00:00'
            ],
            [
                'training_id' => 4,
                'date' => now()->addDays(4)->toDateString(),
                'start_time' => '16:00:00',
                'end_time' => '18:00:00'    
            ],
            [
                'training_id' => 5,
                'date' => now()->addDays(5)->toDateString(),
                'start_time' => '08:30:00',
                'end_time' => '10:30:00'
            ]
        ]);
    }
}