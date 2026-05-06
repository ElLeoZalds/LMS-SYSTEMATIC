<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courses')->insert([
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Professional Excel',
                'description' => 'From basic to advanced levels',
                'hours_count' => 60,
                'reference_price' => 250.00
            ],
            [
                'specialty_id' => 2,
                'banner_path' => null,
                'title' => 'Professional Office Suite',
                'description' => 'Dynamic and effective learning',
                'hours_count' => 50,
                'reference_price' => 200.00
            ],
            [
                'specialty_id' => 3,
                'banner_path' => null,
                'title' => 'Professional AutoCAD',
                'description' => 'Specialized technical training',
                'hours_count' => 70,
                'reference_price' => 300.00
            ],
            [
                'specialty_id' => 4,
                'banner_path' => null,
                'title' => 'Professional Revit',
                'description' => 'Advanced modeling techniques',
                'hours_count' => 80,
                'reference_price' => 320.00
            ],
            [
                'specialty_id' => 5,
                'banner_path' => null,
                'title' => 'Public Speaking',
                'description' => 'Speak in public with confidence',
                'hours_count' => 30,
                'reference_price' => 150.00
            ],
            [
                'specialty_id' => 6,
                'banner_path' => null,
                'title' => 'Graphic Design',
                'description' => 'Design tools and techniques',
                'hours_count' => 60,
                'reference_price' => 280.00
            ],
            [
                'specialty_id' => 7,
                'banner_path' => null,
                'title' => 'Power BI',
                'description' => 'Data analysis and visualization',
                'hours_count' => 40,
                'reference_price' => 220.00
            ],
            [
                'specialty_id' => 8,
                'banner_path' => null,
                'title' => 'Robotics for Kids and Teens',
                'description' => 'Learn by building robots',
                'hours_count' => 45,
                'reference_price' => 180.00
            ],
            [
                'specialty_id' => 9,
                'banner_path' => null,
                'title' => 'Computer Assembly',
                'description' => 'Assembly and repair techniques',
                'hours_count' => 35,
                'reference_price' => 160.00
            ],
            [
                'specialty_id' => 10,
                'banner_path' => null,
                'title' => 'Video Game Development',
                'description' => 'Create your own games from scratch',
                'hours_count' => 75,
                'reference_price' => 350.00
            ],
            [
                'specialty_id' => 11,
                'banner_path' => null,
                'title' => 'Python Programming',
                'description' => 'Code from zero to advanced',
                'hours_count' => 60,
                'reference_price' => 250.00
            ],
            [
                'specialty_id' => 12,
                'banner_path' => null,
                'title' => 'SAP ERP',
                'description' => 'Enterprise resource planning system',
                'hours_count' => 90,
                'reference_price' => 500.00
            ],
            [
                'specialty_id' => 13,
                'banner_path' => null,
                'title' => 'Applied Artificial Intelligence',
                'description' => 'AI for business and technology',
                'hours_count' => 80,
                'reference_price' => 450.00
            ],
            [
                'specialty_id' => 14,
                'banner_path' => null,
                'title' => 'Digital Marketing',
                'description' => 'Strategies and fundamentals',
                'hours_count' => 50,
                'reference_price' => 230.00
            ],
            [
                'specialty_id' => 15,
                'banner_path' => null,
                'title' => 'Video Editing',
                'description' => 'Video production and editing',
                'hours_count' => 55,
                'reference_price' => 260.00
            ],
            [
                'specialty_id' => 16,
                'banner_path' => null,
                'title' => 'Professional Surveying',
                'description' => 'Modern surveying techniques',
                'hours_count' => 70,
                'reference_price' => 300.00
            ]
        ]);
    }
}