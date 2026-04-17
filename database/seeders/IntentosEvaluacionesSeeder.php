<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IntentosEvaluacionesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('intentosevaluaciones')->insert([
            [
                'idmatricula' => 1,
                'idevaluacion' => 1,
                'numero' => 1,
                'fecha' => now(),
                'puntaje' => 18.50
            ],
            [
                'idmatricula' => 2,
                'idevaluacion' => 2,
                'numero' => 1,
                'fecha' => now(),
                'puntaje' => 16.00
            ],
            [
                'idmatricula' => 3,
                'idevaluacion' => 3,
                'numero' => 1,
                'fecha' => now(),
                'puntaje' => 19.00
            ],
            [
                'idmatricula' => 4,
                'idevaluacion' => 4,
                'numero' => 1,
                'fecha' => now(),
                'puntaje' => 15.75
            ],
            [
                'idmatricula' => 5,
                'idevaluacion' => 5,
                'numero' => 1,
                'fecha' => now(),
                'puntaje' => 17.25
            ]
        ]);
    }
}
