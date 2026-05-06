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
            ]
        ]);
    }
}