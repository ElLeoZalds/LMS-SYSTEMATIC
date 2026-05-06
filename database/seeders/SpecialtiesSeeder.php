<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('specialties')->insert([
            [
                'specialty' => 'Professional Excel',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Professional Office Suite',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Professional AutoCAD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Professional Revit',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Public Speaking',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Graphic Design',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Power BI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Robotics for Kids and Teens',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Computer Assembly',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Video Game Development',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Python Programming',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'SAP ERP',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Applied Artificial Intelligence',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Digital Marketing',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Video Editing',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Professional Surveying',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}