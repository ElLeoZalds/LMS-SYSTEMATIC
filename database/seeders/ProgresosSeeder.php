<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgresosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('progresos')->insert([
            [
                'idmatricula' => 1,
                'idcontenido' => 1,
                'porcentaje' => 20.00,
                'fechaactividad' => now(),
                'estado' => 'A'
            ],
            [
                'idmatricula' => 1,
                'idcontenido' => 2,
                'porcentaje' => 40.00,
                'fechaactividad' => now(),
                'estado' => 'A'
            ],
            [
                'idmatricula' => 2,
                'idcontenido' => 3,
                'porcentaje' => 30.00,
                'fechaactividad' => now(),
                'estado' => 'A'
            ],
            [
                'idmatricula' => 3,
                'idcontenido' => 4,
                'porcentaje' => 50.00,
                'fechaactividad' => now(),
                'estado' => 'A'
            ],
            [
                'idmatricula' => 4,
                'idcontenido' => 5,
                'porcentaje' => 60.00,
                'fechaactividad' => now(),
                'estado' => 'A'
            ],
            [
                'idmatricula' => 5,
                'idcontenido' => 6,
                'porcentaje' => 80.00,
                'fechaactividad' => now(),
                'estado' => 'A'
            ]
        ]);
    }
}
