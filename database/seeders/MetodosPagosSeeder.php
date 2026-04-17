<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MetodosPagosSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('metodospagos')->insert([
            [
                'metodopago' => 'Efectivo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'metodopago' => 'Tarjeta de Crédito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'metodopago' => 'Tarjeta de Débito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'metodopago' => 'Transferencia Bancaria',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'metodopago' => 'Yape',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'metodopago' => 'Plin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
