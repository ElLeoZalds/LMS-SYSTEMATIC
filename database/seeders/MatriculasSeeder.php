<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MatriculasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('matriculas')->insert([
            [
                'idcapacitacion' => 1,
                'idalumno' => 4,
                'idadministrador' => 1,
                'fechamatricula' => now(),
                'becaporcentaje' => '10%',
                'estado' => 'A'
            ],
            [
                'idcapacitacion' => 2,
                'idalumno' => 4,
                'idadministrador' => 1,
                'fechamatricula' => now(),
                'becaporcentaje' => null,
                'estado' => 'A'
            ],
            [
                'idcapacitacion' => 3,
                'idalumno' => 2,
                'idadministrador' => 1,
                'fechamatricula' => now(),
                'becaporcentaje' => '5%',
                'estado' => 'A'
            ],
            [
                'idcapacitacion' => 4,
                'idalumno' => 3,
                'idadministrador' => 1,
                'fechamatricula' => now(),
                'becaporcentaje' => null,
                'estado' => 'A'
            ],
            [
                'idcapacitacion' => 5,
                'idalumno' => 4,
                'idadministrador' => 1,
                'fechamatricula' => now(),
                'becaporcentaje' => '15%',
                'estado' => 'A'
            ]
        ]);
    }
}
