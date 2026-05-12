<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contents')->insert([
            [
                'training_id' => 1,
                'description' => 'Introducción a Excel y al entorno de trabajo',
                'title' => 'Fundamentos de Excel',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 1,
                'description' => 'Funciones y fórmulas básicas',
                'title' => 'Fórmulas básicas',
                'type' => 'Document',
                'order_index' => 2
            ],
            [
                'training_id' => 2,
                'description' => 'Herramientas de Word, Excel y PowerPoint',
                'title' => 'Suite ofimática básica',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 3,
                'description' => 'Introducción al diseño técnico',
                'title' => 'Conceptos básicos de AutoCAD',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 4,
                'description' => 'Modelado arquitectónico BIM',
                'title' => 'Introducción a Revit',
                'type' => 'Video',
                'order_index' => 1
            ],
            [
                'training_id' => 5,
                'description' => 'Técnicas de comunicación oral',
                'title' => 'Oratoria básica',
                'type' => 'Document',
                'order_index' => 1
            ],
            [
                'training_id' => 6,
                'description' => 'Herramientas de diseño gráfico',
                'title' => 'Introducción al diseño gráfico',
                'type' => 'Video',
                'order_index' => 1
            ]
        ]);
    }
}