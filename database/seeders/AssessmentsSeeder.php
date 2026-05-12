<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AssessmentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('assessments')->insert([
            [
                'training_id' => 1,
                'title' => 'Evaluación básica de Excel',
                'description' => 'Evaluación de conocimientos básicos de Excel',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 2,
                'title' => 'Evaluación de suite ofimática',
                'description' => 'Evaluación de herramientas ofimáticas',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 3,
                'title' => 'Evaluación de AutoCAD',
                'description' => 'Evaluación de diseño técnico en AutoCAD',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 4,
                'title' => 'Evaluación de Revit',
                'description' => 'Evaluación de modelado BIM en Revit',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'training_id' => 5,
                'title' => 'Evaluación de oratoria',
                'description' => 'Evaluación de habilidades de comunicación oral',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'allowed_attempts' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}