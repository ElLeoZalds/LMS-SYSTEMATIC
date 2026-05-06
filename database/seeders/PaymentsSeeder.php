<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentsSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('payments')->insert([
            [
                'enrollment_id' => 1,
                'payment_method_id' => 5,
                'date' => now(),
                'installment' => 50.00,
                'amount' => 220.00,
                'status' => 'A'
            ],
            [
                'enrollment_id' => 2,
                'payment_method_id' => 6,
                'date' => now(),
                'installment' => 80.00,
                'amount' => 180.00,
                'status' => 'A'
            ],
            [
                'enrollment_id' => 3,
                'payment_method_id' => 1,
                'date' => now(),
                'installment' => 100.00,
                'amount' => 280.00,
                'status' => 'A'
            ],
            [
                'enrollment_id' => 4,
                'payment_method_id' => 2,
                'date' => now(),
                'installment' => 120.00,
                'amount' => 300.00,
                'status' => 'A'
            ],
            [
                'enrollment_id' => 5,
                'payment_method_id' => 5,
                'date' => now(),
                'installment' => 70.00,
                'amount' => 140.00,
                'status' => 'A'
            ]
        ]);
    }
}