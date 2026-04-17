<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CursosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cursos')->insert([
            [
                'idespecialidad' => 1,
                'pathbanner' => null,
                'titulo' => 'Excel Profesional',
                'descripcion' => 'Niveles desde básico hasta avanzado',
                'cantidadhoras' => 60,
                'precioreferencial' => 250.00
            ],
            [
                'idespecialidad' => 2,
                'pathbanner' => null,
                'titulo' => 'Ofimática Profesional',
                'descripcion' => 'Aprendizaje dinámico y efectivo',
                'cantidadhoras' => 50,
                'precioreferencial' => 200.00
            ],
            [
                'idespecialidad' => 3,
                'pathbanner' => null,
                'titulo' => 'AutoCAD Profesional',
                'descripcion' => 'Formación Técnica Especializada',
                'cantidadhoras' => 70,
                'precioreferencial' => 300.00
            ],
            [
                'idespecialidad' => 4,
                'pathbanner' => null,
                'titulo' => 'Revit Profesional',
                'descripcion' => 'Técnicas avanzadas de modelado',
                'cantidadhoras' => 80,
                'precioreferencial' => 320.00
            ],
            [
                'idespecialidad' => 5,
                'pathbanner' => null,
                'titulo' => 'Oratoria',
                'descripcion' => 'Hablar en público con confianza',
                'cantidadhoras' => 30,
                'precioreferencial' => 150.00
            ],
            [
                'idespecialidad' => 6,
                'pathbanner' => null,
                'titulo' => 'Diseño Gráfico',
                'descripcion' => 'Herramientas y técnicas de diseño',
                'cantidadhoras' => 60,
                'precioreferencial' => 280.00
            ],
            [
                'idespecialidad' => 7,
                'pathbanner' => null,
                'titulo' => 'Power BI',
                'descripcion' => 'Análisis de Datos y Visualización',
                'cantidadhoras' => 40,
                'precioreferencial' => 220.00
            ],
            [
                'idespecialidad' => 8,
                'pathbanner' => null,
                'titulo' => 'Robótica para niños y jóvenes',
                'descripcion' => 'Aprende creando robótica',
                'cantidadhoras' => 45,
                'precioreferencial' => 180.00
            ],
            [
                'idespecialidad' => 9,
                'pathbanner' => null,
                'titulo' => 'Ensamblaje de Computadoras',
                'descripcion' => 'Ensamblaje y reparación',
                'cantidadhoras' => 35,
                'precioreferencial' => 160.00
            ],
            [
                'idespecialidad' => 10,
                'pathbanner' => null,
                'titulo' => 'Programación de Videojuegos',
                'descripcion' => 'Programa y crea tus propios juegos',
                'cantidadhoras' => 75,
                'precioreferencial' => 350.00
            ],
            [
                'idespecialidad' => 11,
                'pathbanner' => null,
                'titulo' => 'Python',
                'descripcion' => 'Programa sin límites desde cero',
                'cantidadhoras' => 60,
                'precioreferencial' => 250.00
            ],
            [
                'idespecialidad' => 12,
                'pathbanner' => null,
                'titulo' => 'SAP ERP',
                'descripcion' => 'Administración Empresarial Integrada',
                'cantidadhoras' => 90,
                'precioreferencial' => 500.00
            ],
            [
                'idespecialidad' => 13,
                'pathbanner' => null,
                'titulo' => 'Inteligencia Artificial Aplicada',
                'descripcion' => 'IA aplicada a negocios y tecnología',
                'cantidadhoras' => 80,
                'precioreferencial' => 450.00
            ],
            [
                'idespecialidad' => 14,
                'pathbanner' => null,
                'titulo' => 'Marketing Digital',
                'descripcion' => 'Fundamentos y estrategias efectivas',
                'cantidadhoras' => 50,
                'precioreferencial' => 230.00
            ],
            [
                'idespecialidad' => 15,
                'pathbanner' => null,
                'titulo' => 'Edición de Vídeo',
                'descripcion' => 'Edición y Producción de vídeo',
                'cantidadhoras' => 55,
                'precioreferencial' => 260.00
            ],
            [
                'idespecialidad' => 16,
                'pathbanner' => null,
                'titulo' => 'Topografía Profesional',
                'descripcion' => 'Técnicas modernas de topografía',
                'cantidadhoras' => 70,
                'precioreferencial' => 300.00
            ]
        ]);
    }
}
