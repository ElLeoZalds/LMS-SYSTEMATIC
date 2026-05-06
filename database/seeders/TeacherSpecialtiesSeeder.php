<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSpecialtiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teacher_specialties')->insert([
            [
                'user_id' => 2,
                'specialty_id' => 1
            ],
            [
                'user_id' => 2,
                'specialty_id' => 2
            ],
            [
                'user_id' => 3,
                'specialty_id' => 3
            ],
            [
                'user_id' => 3,
                'specialty_id' => 4
            ]
        ]);
    }
}