<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProgressSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('progress')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('progress')->insert([
            [
                'enrollment_id' => 1,
                'content_id' => 1,
                'percentage' => 5,
                'activity_date' => now()->subDays(19)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 2,
                'content_id' => 2,
                'percentage' => 10,
                'activity_date' => now()->subDays(18)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 3,
                'content_id' => 3,
                'percentage' => 15,
                'activity_date' => now()->subDays(17)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 4,
                'content_id' => 4,
                'percentage' => 20,
                'activity_date' => now()->subDays(16)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 5,
                'content_id' => 5,
                'percentage' => 25,
                'activity_date' => now()->subDays(15)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 6,
                'content_id' => 6,
                'percentage' => 30,
                'activity_date' => now()->subDays(14)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 7,
                'content_id' => 7,
                'percentage' => 35,
                'activity_date' => now()->subDays(13)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 8,
                'content_id' => 8,
                'percentage' => 40,
                'activity_date' => now()->subDays(12)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 9,
                'content_id' => 9,
                'percentage' => 45,
                'activity_date' => now()->subDays(11)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 10,
                'content_id' => 10,
                'percentage' => 50,
                'activity_date' => now()->subDays(10)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 11,
                'content_id' => 11,
                'percentage' => 55,
                'activity_date' => now()->subDays(9)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 12,
                'content_id' => 12,
                'percentage' => 60,
                'activity_date' => now()->subDays(8)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 13,
                'content_id' => 13,
                'percentage' => 65,
                'activity_date' => now()->subDays(7)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 14,
                'content_id' => 14,
                'percentage' => 70,
                'activity_date' => now()->subDays(6)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 15,
                'content_id' => 15,
                'percentage' => 75,
                'activity_date' => now()->subDays(5)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 16,
                'content_id' => 16,
                'percentage' => 80,
                'activity_date' => now()->subDays(4)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 17,
                'content_id' => 17,
                'percentage' => 85,
                'activity_date' => now()->subDays(3)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 18,
                'content_id' => 18,
                'percentage' => 90,
                'activity_date' => now()->subDays(2)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 19,
                'content_id' => 19,
                'percentage' => 95,
                'activity_date' => now()->subDays(1)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'enrollment_id' => 20,
                'content_id' => 20,
                'percentage' => 100,
                'activity_date' => now()->subDays(0)->toDateString(),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
