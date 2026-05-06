<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PeopleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('people')->insert([
            [
                'last_names' => 'Gomez Perez',
                'first_names' => 'Juan Carlos',
                'document_type' => 'DNI',
                'document_number' => '12345678',
                'phone' => '987654321',
                'address' => 'Av. Siempre Viva 123',
                'email' => 'juan@example.com',
                'gender' => 'M',
                'birth_date' => '1990-05-10',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'last_names' => 'Lopez Ramirez',
                'first_names' => 'Maria Elena',
                'document_type' => 'DNI',
                'document_number' => '87654321',
                'phone' => '912345678',
                'address' => 'Jr. Las Flores 456',
                'email' => 'maria@example.com',
                'gender' => 'F',
                'birth_date' => '1995-08-20',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'last_names' => 'Torres Diaz',
                'first_names' => 'Luis Alberto',
                'document_type' => 'DNI',
                'document_number' => '45678912',
                'phone' => '998877665',
                'address' => 'Calle Sol 789',
                'email' => 'luis@example.com',
                'gender' => 'M',
                'birth_date' => '1988-03-15',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'last_names' => 'Rojas Castillo',
                'first_names' => 'Ana Lucia',
                'document_type' => 'DNI',
                'document_number' => '74125896',
                'phone' => '955443322',
                'address' => 'Av. Perú 321',
                'email' => 'ana@example.com',
                'gender' => 'F',
                'birth_date' => '2000-11-05',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}