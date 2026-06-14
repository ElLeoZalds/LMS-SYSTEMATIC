<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AnnouncementsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('announcements')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('announcements')->insert([
            [
                'training_id' => 1,
                'teacher_id' => 2,
                'content' => 'Comunicado demo 1 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 2,
                'teacher_id' => 3,
                'content' => 'Comunicado demo 2 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 3,
                'teacher_id' => 4,
                'content' => 'Comunicado demo 3 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 4,
                'teacher_id' => 5,
                'content' => 'Comunicado demo 4 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 5,
                'teacher_id' => 6,
                'content' => 'Comunicado demo 5 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 6,
                'teacher_id' => 2,
                'content' => 'Comunicado demo 6 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 7,
                'teacher_id' => 3,
                'content' => 'Comunicado demo 7 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 8,
                'teacher_id' => 4,
                'content' => 'Comunicado demo 8 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 9,
                'teacher_id' => 5,
                'content' => 'Comunicado demo 9 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 10,
                'teacher_id' => 6,
                'content' => 'Comunicado demo 10 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 11,
                'teacher_id' => 2,
                'content' => 'Comunicado demo 11 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 12,
                'teacher_id' => 3,
                'content' => 'Comunicado demo 12 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 13,
                'teacher_id' => 4,
                'content' => 'Comunicado demo 13 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 14,
                'teacher_id' => 5,
                'content' => 'Comunicado demo 14 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 15,
                'teacher_id' => 6,
                'content' => 'Comunicado demo 15 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 16,
                'teacher_id' => 2,
                'content' => 'Comunicado demo 16 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 17,
                'teacher_id' => 3,
                'content' => 'Comunicado demo 17 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 18,
                'teacher_id' => 4,
                'content' => 'Comunicado demo 18 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 19,
                'teacher_id' => 5,
                'content' => 'Comunicado demo 19 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'training_id' => 20,
                'teacher_id' => 6,
                'content' => 'Comunicado demo 20 para la capacitacion.',
                'link' => null,
                'attachments' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
