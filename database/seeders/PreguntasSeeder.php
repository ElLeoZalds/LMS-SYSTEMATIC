<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreguntasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('preguntas')->insert([
            [
                'idevaluacion' => 1,
                'textopregunta' => '¿Qué es una celda en Excel?',
                'puntaje' => 10,
                'orden' => 1
            ],
            [
                'idevaluacion' => 1,
                'textopregunta' => '¿Cómo se realiza una suma en Excel?',
                'puntaje' => 10,
                'orden' => 2
            ],
            [
                'idevaluacion' => 2,
                'textopregunta' => '¿Qué herramientas incluye la ofimática?',
                'puntaje' => 10,
                'orden' => 1
            ],
            [
                'idevaluacion' => 3,
                'textopregunta' => '¿Para qué sirve AutoCAD?',
                'puntaje' => 10,
                'orden' => 1
            ],
            [
                'idevaluacion' => 4,
                'textopregunta' => '¿Qué es el modelado BIM en Revit?',
                'puntaje' => 10,
                'orden' => 1
            ],
            [
                'idevaluacion' => 5,
                'textopregunta' => '¿Qué técnicas ayudan a mejorar la oratoria?',
                'puntaje' => 10,
                'orden' => 1
            ]
        ]);
    }
}
