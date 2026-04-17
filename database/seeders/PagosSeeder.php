<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('pagos')->insert([
            [
                'idmatricula' => 1,
                'idmetodo' => 5,
                'fecha' => now(),
                'amortizacion' => 50.00,
                'monto' => 220.00,
                'estado' => 'A'
            ],
            [
                'idmatricula' => 2,
                'idmetodo' => 6,
                'fecha' => now(),
                'amortizacion' => 80.00,
                'monto' => 180.00,
                'estado' => 'A'
            ],
            [
                'idmatricula' => 3,
                'idmetodo' => 1,
                'fecha' => now(),
                'amortizacion' => 100.00,
                'monto' => 280.00,
                'estado' => 'A'
            ],
            [
                'idmatricula' => 4,
                'idmetodo' => 2,
                'fecha' => now(),
                'amortizacion' => 120.00,
                'monto' => 300.00,
                'estado' => 'A'
            ],
            [
                'idmatricula' => 5,
                'idmetodo' => 5,
                'fecha' => now(),
                'amortizacion' => 70.00,
                'monto' => 140.00,
                'estado' => 'A'
            ]
        ]);
    }
}
