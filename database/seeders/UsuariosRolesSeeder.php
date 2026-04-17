<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsuariosRolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('usuariosroles')->insert([
            [
                'idusuario' => 1,
                'idrol' => 1
            ],
            [
                'idusuario' => 2,
                'idrol' => 2
            ],
            [
                'idusuario' => 3,
                'idrol' => 2
            ],
            [
                'idusuario' => 4,
                'idrol' => 3
            ]
        ]);
    }
}
