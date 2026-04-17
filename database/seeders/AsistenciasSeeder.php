<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AsistenciasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('asistencias')->insert([
            [
                'idhorario' => 1,
                'idmatricula' => 1,
                'asistencia' => 'Presente'
            ],
            [
                'idhorario' => 2,
                'idmatricula' => 2,
                'asistencia' => 'Presente'
            ],
            [
                'idhorario' => 3,
                'idmatricula' => 3,
                'asistencia' => 'Tardanza'
            ],
            [
                'idhorario' => 4,
                'idmatricula' => 4,
                'asistencia' => 'Falta'
            ],
            [
                'idhorario' => 5,
                'idmatricula' => 5,
                'asistencia' => 'Presente'
            ]
        ]);
    }
}
