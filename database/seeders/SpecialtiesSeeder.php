<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialtiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('courses')->truncate();
        DB::table('specialties')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        DB::table('specialties')->insert([
            [
                'specialty' => 'Ofimática y Análisis de Datos',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Diseño, Arquitectura e Ingeniería',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Programación y Tecnología',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Robótica y Tecnología Educativa',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Marketing y Producción Multimedia',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'specialty' => 'Desarrollo Personal y Comunicación',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}