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
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 2,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 3,
                'enrollment_id' => 3,
                'attendance' => json_encode(['status' => 'late']),
            ],
            [
                'schedule_id' => 4,
                'enrollment_id' => 4,
                'attendance' => json_encode(['status' => 'absent']),
            ],
            [
                'schedule_id' => 5,
                'enrollment_id' => 5,
                'attendance' => json_encode(['status' => 'present']),
            ],

            [
                'schedule_id' => 1,
                'enrollment_id' => 6,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 7,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 8,
                'attendance' => json_encode(['status' => 'late']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 9,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 10,
                'attendance' => json_encode(['status' => 'absent']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 11,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 12,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 13,
                'attendance' => json_encode(['status' => 'late']),
            ],
            [
                'schedule_id' => 1,
                'enrollment_id' => 14,
                'attendance' => json_encode(['status' => 'present']),
            ],

            [
                'schedule_id' => 2,
                'enrollment_id' => 15,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 16,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 17,
                'attendance' => json_encode(['status' => 'absent']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 18,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 19,
                'attendance' => json_encode(['status' => 'late']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 20,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 21,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 22,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 23,
                'attendance' => json_encode(['status' => 'absent']),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 24,
                'attendance' => json_encode(['status' => 'present']),
            ],

            [
                'schedule_id' => 3,
                'enrollment_id' => 25,
                'attendance' => json_encode(['status' => 'present']),
            ],
            [
                'schedule_id' => 3,
                'enrollment_id' => 26,
                'attendance' => json_encode(['status' => 'late']),
            ],
        ]);
    }
}
