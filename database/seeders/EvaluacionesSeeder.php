<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EvaluacionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('evaluaciones')->insert([
            [
                'idcapacitacion' => 1,
                'titulo' => 'Evaluación Excel Básico',
                'descripcion' => 'Evaluación de conocimientos básicos de Excel',
                'fechainicio' => now(),
                'fechafin' => now()->addDays(7),
                'intentospermitidos' => 3,
                'activo' => true
            ],
            [
                'idcapacitacion' => 2,
                'titulo' => 'Evaluación Ofimática',
                'descripcion' => 'Evaluación de herramientas de ofimática',
                'fechainicio' => now(),
                'fechafin' => now()->addDays(7),
                'intentospermitidos' => 2,
                'activo' => true
            ],
            [
                'idcapacitacion' => 3,
                'titulo' => 'Evaluación AutoCAD',
                'descripcion' => 'Evaluación de diseño técnico en AutoCAD',
                'fechainicio' => now(),
                'fechafin' => now()->addDays(7),
                'intentospermitidos' => 2,
                'activo' => true
            ],
            [
                'idcapacitacion' => 4,
                'titulo' => 'Evaluación Revit',
                'descripcion' => 'Evaluación de modelado BIM en Revit',
                'fechainicio' => now(),
                'fechafin' => now()->addDays(7),
                'intentospermitidos' => 2,
                'activo' => true
            ],
            [
                'idcapacitacion' => 5,
                'titulo' => 'Evaluación Oratoria',
                'descripcion' => 'Evaluación de habilidades de expresión oral',
                'fechainicio' => now(),
                'fechafin' => now()->addDays(7),
                'intentospermitidos' => 3,
                'activo' => true
            ]
        ]);
    }
}
