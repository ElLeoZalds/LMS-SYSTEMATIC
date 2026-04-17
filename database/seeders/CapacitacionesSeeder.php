<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CapacitacionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('capacitaciones')->insert([
            [
                'idcurso' => 1,
                'idprofesor' => 2,
                'idadministrador' => 1,
                'modalidad' => 'Virtual',
                'precio' => 220.00,
                'fechacreacion' => now(),
                'estado' => 'A'
            ],
            [
                'idcurso' => 2,
                'idprofesor' => 3,
                'idadministrador' => 1,
                'modalidad' => 'Presencial',
                'precio' => 180.00,
                'fechacreacion' => now(),
                'estado' => 'A'
            ],
            [
                'idcurso' => 3,
                'idprofesor' => 2,
                'idadministrador' => 1,
                'modalidad' => 'Virtual',
                'precio' => 280.00,
                'fechacreacion' => now(),
                'estado' => 'A'
            ],
            [
                'idcurso' => 4,
                'idprofesor' => 3,
                'idadministrador' => 1,
                'modalidad' => 'Presencial',
                'precio' => 300.00,
                'fechacreacion' => now(),
                'estado' => 'A'
            ],
            [
                'idcurso' => 5,
                'idprofesor' => 2,
                'idadministrador' => 1,
                'modalidad' => 'Virtual',
                'precio' => 140.00,
                'fechacreacion' => now(),
                'estado' => 'A'
            ],
            [
                'idcurso' => 6,
                'idprofesor' => 3,
                'idadministrador' => 1,
                'modalidad' => 'Virtual',
                'precio' => 260.00,
                'fechacreacion' => now(),
                'estado' => 'A'
            ]
        ]);
    }
}
