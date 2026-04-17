<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PersonasSeeder::class,
            RolesSeeder::class,
            UsuariosSeeder::class,
            UsuariosRolesSeeder::class,
            EspecialidadesSeeder::class,
            ProfesoresEspecialidadesSeeder::class,
            CursosSeeder::class,
            CapacitacionesSeeder::class,
            MetodosPagosSeeder::class,
            MatriculasSeeder::class,
            PagosSeeder::class,
            EvaluacionesSeeder::class,
            PreguntasSeeder::class,
            AlternativasSeeder::class,
            ContenidosSeeder::class,
            ProgresosSeeder::class,
            HorariosSeeder::class,
            AsistenciasSeeder::class,
            IntentosEvaluacionesSeeder::class,
        ]);
    }
}
