<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendancesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('attendances')->insert([
            [
                'schedule_id' => 1,
                'enrollment_id' => 1,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 2,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 3,
                'enrollment_id' => 3,
                'attendance' => 'late'
            ],
            [
                'schedule_id' => 4,
                'enrollment_id' => 4,
                'attendance' => 'absent'
            ],
            [
                'schedule_id' => 5,
                'enrollment_id' => 5,
                'attendance' => 'present'
            ]
        ]);
    }
}