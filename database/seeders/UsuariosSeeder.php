<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuarios')->insert([
            [
                'idpersona' => 1,
                'username' => 'juan.gomez',
                'password' => Hash::make('123456'),
                'estado' => 'A',
                'fechacreacion' => now(),
                'fechamodificacion' => now()
            ],
            [
                'idpersona' => 2,
                'username' => 'maria.lopez',
                'password' => Hash::make('123456'),
                'estado' => 'A',
                'fechacreacion' => now(),
                'fechamodificacion' => now()
            ],
            [
                'idpersona' => 3,
                'username' => 'luis.torres',
                'password' => Hash::make('123456'),
                'estado' => 'A',
                'fechacreacion' => now(),
                'fechamodificacion' => now()
            ],
            [
                'idpersona' => 4,
                'username' => 'ana.rojas',
                'password' => Hash::make('123456'),
                'estado' => 'A',
                'fechacreacion' => now(),
                'fechamodificacion' => now()
            ]
        ]);
    }
}
