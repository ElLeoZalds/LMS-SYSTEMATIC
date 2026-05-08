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
            ],

            [
                'schedule_id' => 1,
                'enrollment_id' => 6,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 7,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 8,
                'attendance' => 'late'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 9,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 10,
                'attendance' => 'absent'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 11,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 12,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 13,
                'attendance' => 'late'
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 14,
                'attendance' => 'present'
            ],

            [
                'schedule_id' => 2,
                'enrollment_id' => 15,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 16,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 17,
                'attendance' => 'absent'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 18,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 19,
                'attendance' => 'late'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 20,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 21,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 22,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 23,
                'attendance' => 'absent'
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 24,
                'attendance' => 'present'
            ],

            [
                'schedule_id' => 3,
                'enrollment_id' => 25,
                'attendance' => 'present'
            ],
            [
                'schedule_id' => 3,
                'enrollment_id' => 26,
                'attendance' => 'late'
            ]
        ]);
    }
}
