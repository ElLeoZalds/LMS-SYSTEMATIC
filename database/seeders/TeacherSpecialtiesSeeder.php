<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TeacherSpecialtiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('teacher_specialties')->truncate();

        // Note: Specialty IDs have changed. Update assignments as needed.
        // For now, leaving empty to avoid invalid references.
    }
}