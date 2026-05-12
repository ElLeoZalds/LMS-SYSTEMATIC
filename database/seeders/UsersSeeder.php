<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'person_id' => 1,
                'username' => 'juan.gomez.admin',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 2,
                'username' => 'maria.lopez.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 3,
                'username' => 'luis.torres.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 27,
                'username' => 'roberto.vega.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 28,
                'username' => 'sofia.luna.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 29,
                'username' => 'diego.ponce.teacher',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 4,
                'username' => 'ana.rojas.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'person_id' => 5,
                'username' => 'carlos.martinez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 6,
                'username' => 'laura.garcia.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 7,
                'username' => 'miguel.hernandez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 8,
                'username' => 'isabel.jimenez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 9,
                'username' => 'diego.perez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 10,
                'username' => 'valentina.ramirez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 11,
                'username' => 'andres.flores.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 12,
                'username' => 'camila.gutierrez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 13,
                'username' => 'javier.reyes.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 14,
                'username' => 'gabriela.morales.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 15,
                'username' => 'roberto.ortiz.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 16,
                'username' => 'natalia.delgado.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 17,
                'username' => 'fernando.sanchez.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 18,
                'username' => 'monica.vargas.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 19,
                'username' => 'eduardo.castro.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 20,
                'username' => 'patricia.romero.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 21,
                'username' => 'antonio.ruiz.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 22,
                'username' => 'silvia.mendoza.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 23,
                'username' => 'ricardo.guerrero.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 24,
                'username' => 'daniela.herrera.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 25,
                'username' => 'oscar.medina.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 26,
                'username' => 'veronica.cabrera.student',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}