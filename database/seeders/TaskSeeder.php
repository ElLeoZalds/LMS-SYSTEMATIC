<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('tasks')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('tasks')->insert([
            [
                'training_id' => 1,
                'title' => 'Tarea demo 1',
                'description' => 'Descripcion de la tarea demo 1',
                'due_date' => now()->addDays(11),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 2,
                'title' => 'Tarea demo 2',
                'description' => 'Descripcion de la tarea demo 2',
                'due_date' => now()->addDays(12),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 3,
                'title' => 'Tarea demo 3',
                'description' => 'Descripcion de la tarea demo 3',
                'due_date' => now()->addDays(13),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 4,
                'title' => 'Tarea demo 4',
                'description' => 'Descripcion de la tarea demo 4',
                'due_date' => now()->addDays(14),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 5,
                'title' => 'Tarea demo 5',
                'description' => 'Descripcion de la tarea demo 5',
                'due_date' => now()->addDays(15),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 6,
                'title' => 'Tarea demo 6',
                'description' => 'Descripcion de la tarea demo 6',
                'due_date' => now()->addDays(16),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 7,
                'title' => 'Tarea demo 7',
                'description' => 'Descripcion de la tarea demo 7',
                'due_date' => now()->addDays(17),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 8,
                'title' => 'Tarea demo 8',
                'description' => 'Descripcion de la tarea demo 8',
                'due_date' => now()->addDays(18),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 9,
                'title' => 'Tarea demo 9',
                'description' => 'Descripcion de la tarea demo 9',
                'due_date' => now()->addDays(19),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 10,
                'title' => 'Tarea demo 10',
                'description' => 'Descripcion de la tarea demo 10',
                'due_date' => now()->addDays(20),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 11,
                'title' => 'Tarea demo 11',
                'description' => 'Descripcion de la tarea demo 11',
                'due_date' => now()->addDays(21),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 12,
                'title' => 'Tarea demo 12',
                'description' => 'Descripcion de la tarea demo 12',
                'due_date' => now()->addDays(22),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 13,
                'title' => 'Tarea demo 13',
                'description' => 'Descripcion de la tarea demo 13',
                'due_date' => now()->addDays(23),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 14,
                'title' => 'Tarea demo 14',
                'description' => 'Descripcion de la tarea demo 14',
                'due_date' => now()->addDays(24),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 15,
                'title' => 'Tarea demo 15',
                'description' => 'Descripcion de la tarea demo 15',
                'due_date' => now()->addDays(25),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 16,
                'title' => 'Tarea demo 16',
                'description' => 'Descripcion de la tarea demo 16',
                'due_date' => now()->addDays(26),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 17,
                'title' => 'Tarea demo 17',
                'description' => 'Descripcion de la tarea demo 17',
                'due_date' => now()->addDays(27),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 18,
                'title' => 'Tarea demo 18',
                'description' => 'Descripcion de la tarea demo 18',
                'due_date' => now()->addDays(28),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 19,
                'title' => 'Tarea demo 19',
                'description' => 'Descripcion de la tarea demo 19',
                'due_date' => now()->addDays(29),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 20,
                'title' => 'Tarea demo 20',
                'description' => 'Descripcion de la tarea demo 20',
                'due_date' => now()->addDays(30),
                'file_path' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
