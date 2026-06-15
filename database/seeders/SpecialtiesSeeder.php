<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SpecialtiesSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('specialties')->truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('specialties')->insert([
            [
                'specialty' => 'Ofimatica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Diseno y Arquitectura',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Programacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Robotica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Marketing Digital',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Comunicacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Analisis de Datos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Gestion Empresarial',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Finanzas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Idiomas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Ciberseguridad',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Redes',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Multimedia',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Educacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Logistica',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Recursos Humanos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Ventas',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Contabilidad',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Innovacion',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'specialty' => 'Emprendimiento',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
