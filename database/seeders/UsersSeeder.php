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
                'username' => 'juan.gomez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 2,
                'username' => 'maria.lopez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 3,
                'username' => 'luis.torres',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 4,
                'username' => 'ana.rojas',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],

            [
                'person_id' => 5,
                'username' => 'carlos.martinez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 6,
                'username' => 'laura.garcia',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 7,
                'username' => 'miguel.hernandez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 8,
                'username' => 'isabel.jimenez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 9,
                'username' => 'diego.perez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 10,
                'username' => 'valentina.ramirez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 11,
                'username' => 'andres.flores',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 12,
                'username' => 'camila.gutierrez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 13,
                'username' => 'javier.reyes',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 14,
                'username' => 'gabriela.morales',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 15,
                'username' => 'roberto.ortiz',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 16,
                'username' => 'natalia.delgado',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 17,
                'username' => 'fernando.sanchez',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 18,
                'username' => 'monica.vargas',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 19,
                'username' => 'eduardo.castro',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 20,
                'username' => 'patricia.romero',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 21,
                'username' => 'antonio.ruiz',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 22,
                'username' => 'silvia.mendoza',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 23,
                'username' => 'ricardo.guerrero',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 24,
                'username' => 'daniela.herrera',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 25,
                'username' => 'oscar.medina',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'person_id' => 26,
                'username' => 'veronica.cabrera',
                'password' => Hash::make('123456'),
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}