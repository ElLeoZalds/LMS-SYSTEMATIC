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
                'payment_method' => 'Cash',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Credit Card',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Debit Card',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'payment_method' => 'Bank Transfer',
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