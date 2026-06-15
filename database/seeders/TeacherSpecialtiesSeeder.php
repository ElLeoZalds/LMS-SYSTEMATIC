<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherSpecialtiesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('teacher_specialties')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('teacher_specialties')->insert([
            [
                'user_id' => 2,
                'specialty_id' => 1,
            ],
            [
                'user_id' => 3,
                'specialty_id' => 2,
            ],
            [
                'user_id' => 4,
                'specialty_id' => 3,
            ],
            [
                'user_id' => 5,
                'specialty_id' => 4,
            ],
            [
                'user_id' => 6,
                'specialty_id' => 5,
            ],
            [
                'user_id' => 2,
                'specialty_id' => 6,
            ],
            [
                'user_id' => 3,
                'specialty_id' => 7,
            ],
            [
                'user_id' => 4,
                'specialty_id' => 8,
            ],
            [
                'user_id' => 5,
                'specialty_id' => 9,
            ],
            [
                'user_id' => 6,
                'specialty_id' => 10,
            ],
            [
                'user_id' => 2,
                'specialty_id' => 11,
            ],
            [
                'user_id' => 3,
                'specialty_id' => 12,
            ],
            [
                'user_id' => 4,
                'specialty_id' => 13,
            ],
            [
                'user_id' => 5,
                'specialty_id' => 14,
            ],
            [
                'user_id' => 6,
                'specialty_id' => 15,
            ],
            [
                'user_id' => 2,
                'specialty_id' => 16,
            ],
            [
                'user_id' => 3,
                'specialty_id' => 17,
            ],
            [
                'user_id' => 4,
                'specialty_id' => 18,
            ],
            [
                'user_id' => 5,
                'specialty_id' => 19,
            ],
            [
                'user_id' => 6,
                'specialty_id' => 20,
            ],
        ]);
    }
}
