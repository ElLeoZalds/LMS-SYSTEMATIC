<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PersonasSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('personas')->insert([
            [
                'apellidos' => 'Gomez Perez',
                'nombres' => 'Juan Carlos',
                'tipodoc' => 'DNI',
                'numdoc' => '12345678',
                'telefono' => '987654321',
                'direccion' => 'Av. Siempre Viva 123',
                'email' => 'juan@example.com',
                'genero' => 'M',
                'fechanac' => '1990-05-10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'apellidos' => 'Lopez Ramirez',
                'nombres' => 'Maria Elena',
                'tipodoc' => 'DNI',
                'numdoc' => '87654321',
                'telefono' => '912345678',
                'direccion' => 'Jr. Las Flores 456',
                'email' => 'maria@example.com',
                'genero' => 'F',
                'fechanac' => '1995-08-20',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'apellidos' => 'Torres Diaz',
                'nombres' => 'Luis Alberto',
                'tipodoc' => 'DNI',
                'numdoc' => '45678912',
                'telefono' => '998877665',
                'direccion' => 'Calle Sol 789',
                'email' => 'luis@example.com',
                'genero' => 'M',
                'fechanac' => '1988-03-15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'apellidos' => 'Rojas Castillo',
                'nombres' => 'Ana Lucia',
                'tipodoc' => 'DNI',
                'numdoc' => '74125896',
                'telefono' => '955443322',
                'direccion' => 'Av. Perú 321',
                'email' => 'ana@example.com',
                'genero' => 'F',
                'fechanac' => '2000-11-05',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
