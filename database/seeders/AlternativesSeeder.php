<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlternativesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('alternatives')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('alternatives')->insert([
            [
                'question_id' => 1,
                'option_text' => 'Alternativa correcta demo 1',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 2,
                'option_text' => 'Alternativa correcta demo 2',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 3,
                'option_text' => 'Alternativa correcta demo 3',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 4,
                'option_text' => 'Alternativa correcta demo 4',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 5,
                'option_text' => 'Alternativa correcta demo 5',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 6,
                'option_text' => 'Alternativa correcta demo 6',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 7,
                'option_text' => 'Alternativa correcta demo 7',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 8,
                'option_text' => 'Alternativa correcta demo 8',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 9,
                'option_text' => 'Alternativa correcta demo 9',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 10,
                'option_text' => 'Alternativa correcta demo 10',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 11,
                'option_text' => 'Alternativa correcta demo 11',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 12,
                'option_text' => 'Alternativa correcta demo 12',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 13,
                'option_text' => 'Alternativa correcta demo 13',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 14,
                'option_text' => 'Alternativa correcta demo 14',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 15,
                'option_text' => 'Alternativa correcta demo 15',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 16,
                'option_text' => 'Alternativa correcta demo 16',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 17,
                'option_text' => 'Alternativa correcta demo 17',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 18,
                'option_text' => 'Alternativa correcta demo 18',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 19,
                'option_text' => 'Alternativa correcta demo 19',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'question_id' => 20,
                'option_text' => 'Alternativa correcta demo 20',
                'is_correct' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
