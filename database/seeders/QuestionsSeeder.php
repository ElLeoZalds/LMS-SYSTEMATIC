<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuestionsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('questions')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('questions')->insert([
            [
                'assessment_id' => 1,
                'question_text' => 'Pregunta demo 1: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 2,
                'question_text' => 'Pregunta demo 2: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 3,
                'question_text' => 'Pregunta demo 3: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 4,
                'question_text' => 'Pregunta demo 4: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 5,
                'question_text' => 'Pregunta demo 5: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 6,
                'question_text' => 'Pregunta demo 6: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 7,
                'question_text' => 'Pregunta demo 7: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 8,
                'question_text' => 'Pregunta demo 8: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 9,
                'question_text' => 'Pregunta demo 9: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 10,
                'question_text' => 'Pregunta demo 10: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 11,
                'question_text' => 'Pregunta demo 11: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 12,
                'question_text' => 'Pregunta demo 12: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 13,
                'question_text' => 'Pregunta demo 13: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 14,
                'question_text' => 'Pregunta demo 14: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 15,
                'question_text' => 'Pregunta demo 15: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 16,
                'question_text' => 'Pregunta demo 16: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 17,
                'question_text' => 'Pregunta demo 17: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 18,
                'question_text' => 'Pregunta demo 18: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 19,
                'question_text' => 'Pregunta demo 19: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'assessment_id' => 20,
                'question_text' => 'Pregunta demo 20: selecciona la alternativa correcta.',
                'image_path' => null,
                'score' => 1,
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
