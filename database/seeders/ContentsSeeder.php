<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ContentsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('contents')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('contents')->insert([
            [
                'training_id' => 1,
                'description' => 'Contenido demo para la capacitacion 1',
                'title' => 'Contenido demo 1',
                'type' => 'video',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 2,
                'description' => 'Contenido demo para la capacitacion 2',
                'title' => 'Contenido demo 2',
                'type' => 'pdf',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 3,
                'description' => 'Contenido demo para la capacitacion 3',
                'title' => 'Contenido demo 3',
                'type' => 'link',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 4,
                'description' => 'Contenido demo para la capacitacion 4',
                'title' => 'Contenido demo 4',
                'type' => 'text',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 5,
                'description' => 'Contenido demo para la capacitacion 5',
                'title' => 'Contenido demo 5',
                'type' => 'video',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 6,
                'description' => 'Contenido demo para la capacitacion 6',
                'title' => 'Contenido demo 6',
                'type' => 'pdf',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 7,
                'description' => 'Contenido demo para la capacitacion 7',
                'title' => 'Contenido demo 7',
                'type' => 'link',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 8,
                'description' => 'Contenido demo para la capacitacion 8',
                'title' => 'Contenido demo 8',
                'type' => 'text',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 9,
                'description' => 'Contenido demo para la capacitacion 9',
                'title' => 'Contenido demo 9',
                'type' => 'video',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 10,
                'description' => 'Contenido demo para la capacitacion 10',
                'title' => 'Contenido demo 10',
                'type' => 'pdf',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 11,
                'description' => 'Contenido demo para la capacitacion 11',
                'title' => 'Contenido demo 11',
                'type' => 'link',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 12,
                'description' => 'Contenido demo para la capacitacion 12',
                'title' => 'Contenido demo 12',
                'type' => 'text',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 13,
                'description' => 'Contenido demo para la capacitacion 13',
                'title' => 'Contenido demo 13',
                'type' => 'video',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 14,
                'description' => 'Contenido demo para la capacitacion 14',
                'title' => 'Contenido demo 14',
                'type' => 'pdf',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 15,
                'description' => 'Contenido demo para la capacitacion 15',
                'title' => 'Contenido demo 15',
                'type' => 'link',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 16,
                'description' => 'Contenido demo para la capacitacion 16',
                'title' => 'Contenido demo 16',
                'type' => 'text',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 17,
                'description' => 'Contenido demo para la capacitacion 17',
                'title' => 'Contenido demo 17',
                'type' => 'video',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 18,
                'description' => 'Contenido demo para la capacitacion 18',
                'title' => 'Contenido demo 18',
                'type' => 'pdf',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 19,
                'description' => 'Contenido demo para la capacitacion 19',
                'title' => 'Contenido demo 19',
                'type' => 'link',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 20,
                'description' => 'Contenido demo para la capacitacion 20',
                'title' => 'Contenido demo 20',
                'type' => 'text',
                'order_index' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
