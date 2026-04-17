<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesoresEspecialidadesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('profesorespecialidades')->insert([
            [
                'idusuario' => 2,
                'idespecialidad' => 1
            ],
            [
                'idusuario' => 2,
                'idespecialidad' => 2
            ],
            [
                'idusuario' => 3,
                'idespecialidad' => 3
            ],
            [
                'idusuario' => 3,
                'idespecialidad' => 4
            ]
        ]);
    }
}
