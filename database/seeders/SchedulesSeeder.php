<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchedulesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('schedules')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('schedules')->insert([
            [
                'training_id' => 1,
                'date' => now()->addDays(1)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 2,
                'date' => now()->addDays(2)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 3,
                'date' => now()->addDays(3)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 4,
                'date' => now()->addDays(4)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 5,
                'date' => now()->addDays(5)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 6,
                'date' => now()->addDays(6)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 7,
                'date' => now()->addDays(7)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 8,
                'date' => now()->addDays(8)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 9,
                'date' => now()->addDays(9)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 10,
                'date' => now()->addDays(10)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 11,
                'date' => now()->addDays(11)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 12,
                'date' => now()->addDays(12)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 13,
                'date' => now()->addDays(13)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 14,
                'date' => now()->addDays(14)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 15,
                'date' => now()->addDays(15)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 16,
                'date' => now()->addDays(16)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 17,
                'date' => now()->addDays(17)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 18,
                'date' => now()->addDays(18)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 19,
                'date' => now()->addDays(19)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 20,
                'date' => now()->addDays(20)->toDateString(),
                'start_time' => '18:00:00',
                'end_time' => '20:00:00',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
