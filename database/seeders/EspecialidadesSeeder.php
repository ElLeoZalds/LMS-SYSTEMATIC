<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EspecialidadesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('especialidades')->insert([
            [
                'especialidad' => 'Excel Profesional',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Ofimática Profesional',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'AutoCAD Profesional',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Revit Profesional',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Oratoria',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Diseño Gráfico',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Power BI',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Robótica para niños y jóvenes',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Ensamblaje de Computadoras',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Programación de Videojuegos',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Python',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'SAP ERP',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Inteligencia Artificial Aplicada',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Marketing Digital',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Edición de Vídeo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'especialidad' => 'Topografía Profesional',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}