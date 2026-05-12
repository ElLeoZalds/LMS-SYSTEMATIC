<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payment_methods')->insert([
            [
                'payment_method' => 'Efectivo',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Tarjeta de crédito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Tarjeta de débito',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Transferencia bancaria',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Yape',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Plin',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}