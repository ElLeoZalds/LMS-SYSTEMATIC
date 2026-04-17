<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternativasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alternativas')->insert([
            // Pregunta 1
            [
                'idpregunta' => 1,
                'textoopcion' => 'Una celda es la intersección de una fila y una columna',
                'respuestacorrecta' => true
            ],
            [
                'idpregunta' => 1,
                'textoopcion' => 'Es un tipo de gráfico en Excel',
                'respuestacorrecta' => false
            ],
            [
                'idpregunta' => 1,
                'textoopcion' => 'Es un archivo de Word',
                'respuestacorrecta' => false
            ],

            // Pregunta 2
            [
                'idpregunta' => 2,
                'textoopcion' => 'Usando la función SUMA() o el símbolo +',
                'respuestacorrecta' => true
            ],
            [
                'idpregunta' => 2,
                'textoopcion' => 'Solo escribiendo números sin fórmulas',
                'respuestacorrecta' => false
            ],
            [
                'idpregunta' => 2,
                'textoopcion' => 'Insertando imágenes',
                'respuestacorrecta' => false
            ],

            // Pregunta 3
            [
                'idpregunta' => 3,
                'textoopcion' => 'Incluye Word, Excel y PowerPoint para tareas de oficina',
                'respuestacorrecta' => true
            ],
            [
                'idpregunta' => 3,
                'textoopcion' => 'Solo sirve para diseño gráfico',
                'respuestacorrecta' => false
            ],
            [
                'idpregunta' => 3,
                'textoopcion' => 'Es un sistema operativo',
                'respuestacorrecta' => false
            ],

            // Pregunta 4
            [
                'idpregunta' => 4,
                'textoopcion' => 'Software para diseño y dibujo técnico en 2D y 3D',
                'respuestacorrecta' => true
            ],
            [
                'idpregunta' => 4,
                'textoopcion' => 'Un programa de edición de video',
                'respuestacorrecta' => false
            ],

            // Pregunta 5
            [
                'idpregunta' => 5,
                'textoopcion' => 'Modelado inteligente de información de construcción',
                'respuestacorrecta' => true
            ],
            [
                'idpregunta' => 5,
                'textoopcion' => 'Un antivirus',
                'respuestacorrecta' => false
            ],

            // Pregunta 6
            [
                'idpregunta' => 6,
                'textoopcion' => 'Práctica, dicción y control de nervios',
                'respuestacorrecta' => true
            ],
            [
                'idpregunta' => 6,
                'textoopcion' => 'Solo leer diapositivas',
                'respuestacorrecta' => false
            ]
        ]);
    }
}
