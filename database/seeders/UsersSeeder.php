<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            [
                'person_id' => 1,
                'username' => 'juan.gomez.admin',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 2,
                'username' => 'maria.lopez.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 3,
                'username' => 'luis.torres.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 4,
                'username' => 'ana.rojas.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 5,
                'username' => 'carlos.martinez.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 6,
                'username' => 'laura.garcia.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 7,
                'username' => 'miguel.hernandez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 8,
                'username' => 'isabel.jimenez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 9,
                'username' => 'diego.perez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 10,
                'username' => 'valentina.ramirez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 11,
                'username' => 'andres.flores.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 12,
                'username' => 'camila.gutierrez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 13,
                'username' => 'javier.reyes.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 14,
                'username' => 'gabriela.morales.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 15,
                'username' => 'roberto.ortiz.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 16,
                'username' => 'natalia.delgado.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 17,
                'username' => 'fernando.sanchez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 18,
                'username' => 'monica.vargas.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 19,
                'username' => 'eduardo.castro.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'person_id' => 20,
                'username' => 'patricia.romero.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
