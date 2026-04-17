<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContenidosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('contenidos')->insert([
            [
                'idcapacitacion' => 1,
                'descripcion' => 'Introducción a Excel y entorno de trabajo',
                'titulo' => 'Fundamentos de Excel',
                'tipo' => 'Video',
                'orden' => 1
            ],
            [
                'idcapacitacion' => 1,
                'descripcion' => 'Funciones básicas y fórmulas',
                'titulo' => 'Fórmulas Básicas',
                'tipo' => 'Documento',
                'orden' => 2
            ],
            [
                'idcapacitacion' => 2,
                'descripcion' => 'Herramientas de Word, Excel y PowerPoint',
                'titulo' => 'Ofimática Básica',
                'tipo' => 'Video',
                'orden' => 1
            ],
            [
                'idcapacitacion' => 3,
                'descripcion' => 'Introducción al diseño técnico',
                'titulo' => 'AutoCAD Básico',
                'tipo' => 'Video',
                'orden' => 1
            ],
            [
                'idcapacitacion' => 4,
                'descripcion' => 'Modelado arquitectónico BIM',
                'titulo' => 'Revit Introducción',
                'tipo' => 'Video',
                'orden' => 1
            ],
            [
                'idcapacitacion' => 5,
                'descripcion' => 'Técnicas de comunicación oral',
                'titulo' => 'Oratoria Básica',
                'tipo' => 'Documento',
                'orden' => 1
            ],
            [
                'idcapacitacion' => 6,
                'descripcion' => 'Herramientas de diseño gráfico',
                'titulo' => 'Diseño Gráfico Intro',
                'tipo' => 'Video',
                'orden' => 1
            ]
        ]);
    }
}
