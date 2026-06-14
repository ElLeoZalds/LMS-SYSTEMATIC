<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AttendancesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('attendances')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('attendances')->insert([
            [
                'schedule_id' => 1,
                'enrollment_id' => 1,
                'attendance' => json_encode(['status' => 'present']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 2,
                'enrollment_id' => 2,
                'attendance' => json_encode(['status' => 'absent']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 3,
                'enrollment_id' => 3,
                'attendance' => json_encode(['status' => 'late']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 4,
                'enrollment_id' => 4,
                'attendance' => json_encode(['status' => 'justified']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 5,
                'enrollment_id' => 5,
                'attendance' => json_encode(['status' => 'present']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 6,
                'enrollment_id' => 6,
                'attendance' => json_encode(['status' => 'absent']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 7,
                'enrollment_id' => 7,
                'attendance' => json_encode(['status' => 'late']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 8,
                'enrollment_id' => 8,
                'attendance' => json_encode(['status' => 'justified']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 9,
                'enrollment_id' => 9,
                'attendance' => json_encode(['status' => 'present']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 10,
                'enrollment_id' => 10,
                'attendance' => json_encode(['status' => 'absent']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 11,
                'enrollment_id' => 11,
                'attendance' => json_encode(['status' => 'late']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 12,
                'enrollment_id' => 12,
                'attendance' => json_encode(['status' => 'justified']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 13,
                'enrollment_id' => 13,
                'attendance' => json_encode(['status' => 'present']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 14,
                'enrollment_id' => 14,
                'attendance' => json_encode(['status' => 'absent']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 15,
                'enrollment_id' => 15,
                'attendance' => json_encode(['status' => 'late']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 16,
                'enrollment_id' => 16,
                'attendance' => json_encode(['status' => 'justified']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 17,
                'enrollment_id' => 17,
                'attendance' => json_encode(['status' => 'present']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 18,
                'enrollment_id' => 18,
                'attendance' => json_encode(['status' => 'absent']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 19,
                'enrollment_id' => 19,
                'attendance' => json_encode(['status' => 'late']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'schedule_id' => 20,
                'enrollment_id' => 20,
                'attendance' => json_encode(['status' => 'justified']),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
