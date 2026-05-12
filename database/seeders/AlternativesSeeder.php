<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AlternativesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('alternatives')->insert([
            // Question 1
            [
                'question_id' => 1,
                'option_text' => 'Una celda es la intersección de una fila y una columna',
                'is_correct' => true
            ],
            [
                'question_id' => 1,
                'option_text' => 'Es un tipo de gráfico en Excel',
                'is_correct' => false
            ],
            [
                'question_id' => 1,
                'option_text' => 'Es un archivo de Word',
                'is_correct' => false
            ],

            // Question 2
            [
                'question_id' => 2,
                'option_text' => 'Usando la función SUMA() o el símbolo +',
                'is_correct' => true
            ],
            [
                'question_id' => 2,
                'option_text' => 'Solo escribir números sin fórmulas',
                'is_correct' => false
            ],
            [
                'question_id' => 2,
                'option_text' => 'Insertar imágenes',
                'is_correct' => false
            ],

            // Question 3
            [
                'question_id' => 3,
                'option_text' => 'Incluye Word, Excel y PowerPoint para tareas ofimáticas',
                'is_correct' => true
            ],
            [
                'question_id' => 3,
                'option_text' => 'Solo se usa para diseño gráfico',
                'is_correct' => false
            ],
            [
                'question_id' => 3,
                'option_text' => 'Es un sistema operativo',
                'is_correct' => false
            ],

            // Question 4
            [
                'question_id' => 4,
                'option_text' => 'Software para diseño y dibujo técnico en 2D y 3D',
                'is_correct' => true
            ],
            [
                'question_id' => 4,
                'option_text' => 'Un programa de edición de video',
                'is_correct' => false
            ],

            // Question 5
            [
                'question_id' => 5,
                'option_text' => 'Modelado de Información de Construcción',
                'is_correct' => true
            ],
            [
                'question_id' => 5,
                'option_text' => 'Un software antivirus',
                'is_correct' => false
            ],

            // Question 6
            [
                'question_id' => 6,
                'option_text' => 'Práctica, dicción y control de nervios',
                'is_correct' => true
            ],
            [
                'question_id' => 6,
                'option_text' => 'Solo leer las diapositivas',
                'is_correct' => false
            ],
        ]);
    }
}