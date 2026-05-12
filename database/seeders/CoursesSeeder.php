<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoursesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('courses')->insert([
            // Ofimática y Análisis de Datos (ID 1)
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Excel Profesional',
                'description' => 'Domina Excel desde básico hasta avanzado',
                'hours_count' => 60,
                'reference_price' => 250.00
            ],
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Excel Macros',
                'description' => 'Automatiza tareas con macros en Excel',
                'hours_count' => 40,
                'reference_price' => 200.00
            ],
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Excel Financiero',
                'description' => 'Excel para análisis financiero',
                'hours_count' => 50,
                'reference_price' => 220.00
            ],
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Power BI',
                'description' => 'Análisis y visualización de datos',
                'hours_count' => 40,
                'reference_price' => 220.00
            ],
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Microsoft Project',
                'description' => 'Gestión de proyectos con MS Project',
                'hours_count' => 35,
                'reference_price' => 180.00
            ],
            [
                'specialty_id' => 1,
                'banner_path' => null,
                'title' => 'Ofimática Profesional',
                'description' => 'Suite ofimática completa',
                'hours_count' => 50,
                'reference_price' => 200.00
            ],
            // Diseño, Arquitectura e Ingeniería (ID 2)
            [
                'specialty_id' => 2,
                'banner_path' => null,
                'title' => 'AutoCAD Profesional',
                'description' => 'Diseño técnico con AutoCAD',
                'hours_count' => 70,
                'reference_price' => 300.00
            ],
            [
                'specialty_id' => 2,
                'banner_path' => null,
                'title' => 'Revit Profesional',
                'description' => 'Modelado BIM con Revit',
                'hours_count' => 80,
                'reference_price' => 320.00
            ],
            [
                'specialty_id' => 2,
                'banner_path' => null,
                'title' => 'Diseño Gráfico Profesional',
                'description' => 'Herramientas y técnicas de diseño gráfico',
                'hours_count' => 60,
                'reference_price' => 280.00
            ],
            [
                'specialty_id' => 2,
                'banner_path' => null,
                'title' => 'Topografía Profesional',
                'description' => 'Técnicas modernas de topografía',
                'hours_count' => 70,
                'reference_price' => 300.00
            ],
            // Programación y Tecnología (ID 3)
            [
                'specialty_id' => 3,
                'banner_path' => null,
                'title' => 'Python',
                'description' => 'Programación desde cero hasta avanzado',
                'hours_count' => 60,
                'reference_price' => 250.00
            ],
            [
                'specialty_id' => 3,
                'banner_path' => null,
                'title' => 'Programación de Videojuegos',
                'description' => 'Crea tus propios juegos desde cero',
                'hours_count' => 75,
                'reference_price' => 350.00
            ],
            [
                'specialty_id' => 3,
                'banner_path' => null,
                'title' => 'Inteligencia Artificial Aplicada',
                'description' => 'IA para negocios y tecnología',
                'hours_count' => 80,
                'reference_price' => 450.00
            ],
            [
                'specialty_id' => 3,
                'banner_path' => null,
                'title' => 'SAP ERP',
                'description' => 'Sistema de planificación de recursos empresariales',
                'hours_count' => 90,
                'reference_price' => 500.00
            ],
            [
                'specialty_id' => 3,
                'banner_path' => null,
                'title' => 'Ensamblaje de Computadoras',
                'description' => 'Técnicas de ensamblaje y reparación',
                'hours_count' => 35,
                'reference_price' => 160.00
            ],
            // Robótica y Tecnología Educativa (ID 4)
            [
                'specialty_id' => 4,
                'banner_path' => null,
                'title' => 'Robótica para Niños y Jóvenes',
                'description' => 'Aprende construyendo robots',
                'hours_count' => 45,
                'reference_price' => 180.00
            ],
            // Marketing y Producción Multimedia (ID 5)
            [
                'specialty_id' => 5,
                'banner_path' => null,
                'title' => 'Marketing Digital',
                'description' => 'Estrategias y fundamentos del marketing digital',
                'hours_count' => 50,
                'reference_price' => 230.00
            ],
            [
                'specialty_id' => 5,
                'banner_path' => null,
                'title' => 'Edición de Video',
                'description' => 'Producción y edición de video',
                'hours_count' => 55,
                'reference_price' => 260.00
            ],
            // Desarrollo Personal y Comunicación (ID 6)
            [
                'specialty_id' => 6,
                'banner_path' => null,
                'title' => 'Oratoria',
                'description' => 'Habla en público con confianza',
                'hours_count' => 30,
                'reference_price' => 150.00
            ]
        ]);
    }
}